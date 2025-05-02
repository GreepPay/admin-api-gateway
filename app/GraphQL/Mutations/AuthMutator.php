<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLException;
use App\Models\Auth\User;
use App\Models\Wallet\Wallet;
use App\Services\AuthService;
use App\Services\BlockchainService;
use App\Services\NotificationService;
use App\Services\UserService;
use App\Services\WalletService;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

final class AuthMutator
{
    use FileUploadTrait;

    /**
     * @var AuthService
     */
    protected $authService;
    /**
     * @var NotificationService
     */
    protected $notificationService;
    /**
     * @var UserService
     */
    protected $userService;
    /**
     * @var BlockchainService
     */
    protected $blockchainService;
    /**
     * @var WalletService
     */
    protected $walletService;

    /**
     * AuthMutator constructor.
     */
    public function __construct()
    {
        $this->authService = new AuthService();
        $this->notificationService = new NotificationService();
        $this->userService = new UserService();
        $this->blockchainService = new BlockchainService();
        $this->walletService = new WalletService();
    }

    /**
     * Sign in a user and return a token.
     *
     * @param mixed $_
     * @param array $args
     * @return array
     */
    public function signIn($_, array $args): array
    {
        $userAuth = $this->authService->authenticateUser([
            "username" => $args["email"],
            "password" => $args["password"],
        ]);

        $user = User::with('profile')
            ->where("id", $userAuth["data"]["user"]["id"])
            ->firstOrFail();

        $allowedUserTypes = ['Admin'];

        if (!in_array($user->profile->user_type ?? '', $allowedUserTypes)) {
            throw new GraphQLException("Unauthorized: only admins can log in here.");
        }

        return [
            "token" => $userAuth["data"]["token"],
            "user" => $user,
        ];
    }

    /**
     * Sign up a new user.
     *
     * @param mixed $_
     * @param array $args
     * @return array
     */

    public function signUp($_, array $args): User
    {
        $otp = rand(100000, 999999);

        $authUser = $this->authService->saveUser([
            "firstName" => "Pending",
            "lastName" => "Activation",
            "email" => $args["email"],
            "password" => bcrypt(Str::random(12)),
            "role" => "Admin",
            "otp" => $otp,
        ])["data"];

        $this->userService->createProfile([
            "user_type" => "Admin",
            "auth_user_id" => $authUser["id"],
            "default_currency" => "USD",
            "profileData" => [
                "country" => null,
                "city" => null,
            ],
        ]);

        // Send OTP notification
        $this->notificationService->sendNotification([
            "auth_user_id" => $authUser["id"],
            "type" => "email",
            "email" => $authUser["email"],
            "title" => "Admin Account Activation",
            "content" => "Hello Admin, your OTP is $otp. Use it to activate your account.",
            "template_id" => 1,
            "template_data" => [
                "username" => "Admin",
                "otp" => $otp,
            ]
        ]);

        return User::query()->where("id", $authUser["id"])->first();
    }

    public function activateAdminAccount($_, array $args): User
    {
        $user = User::where('email', $args['email'])->firstOrFail();

        if (
            !$user->otp ||
            $user->otp !== $args['otp'] ||
            $user->otp_expired_at < now()
        ) {
            throw new GraphQLException("Invalid or expired OTP.");
        }

        $this->authService->updateAuthUserProfile([
            'auth_user_id' => $user->id,
            'first_name' => $args['first_name'],
            'last_name' => $args['last_name'],
            'otp' => null,
            'otp_expired_at' => null,
        ]);

        $this->authService->updatePassword([
            "currentPassword" => null,
            "newPassword" => $args["password"],
        ]);

        return User::query()->where("id", $user["id"])->first();
    }

    /**
     * Resend email OTP to a user.
     *
     * @param mixed $_
     * @param array $args
     * @return bool
     * @throws GraphQLException
     */
    public function resendEmailOTP($_, array $args): bool
    {
        $userWithEmail = User::query()->where("email", $args["email"])->first();

        if (!$userWithEmail) {
            throw new GraphQLException("User with email not found");
        }

        // First reset user OTP
        $this->authService->resetOtp([
            "email" => $userWithEmail->email,
        ]);

        // TODO: Implement email verification notification

        return true;
    }

    /**
     * Send reset password OTP to a user.
     *
     * @param mixed $_
     * @param array $args
     * @return bool
     * @throws GraphQLException
     */
    public function sendResetPasswordOTP($_, array $args): bool
    {
        $userWithEmail = User::query()->where("email", $args["email"])->first();

        if (!$userWithEmail) {
            throw new GraphQLException("User with email not found");
        }

        // First reset user OTP
        $this->authService->resetOtp([
            "email" => $userWithEmail->email,
        ]);

        // TODO: Implement email reset password notification

        return true;
    }

    /**
     * Reset the password of a user.
     *
     * @param mixed $_
     * @param array $args
     * @return bool
     * @throws GraphQLException
     */
    public function resetPassword($_, array $args): bool
    {
        $userWithUuid = User::query()
            ->where("uuid", $args["user_uuid"])
            ->first();

        if (!$userWithUuid) {
            throw new GraphQLException("User not found");
        }

        // First verify the user OTP
        $this->authService->verifyUserOtp([
            "userUuid" => $userWithUuid->uuid,
            "email" => $userWithUuid->email,
            "otp" => $args["otp"],
        ]);

        // If it succeeds, reset the password
        $this->authService->updatePassword([
            "currentPassword" => null,
            "newPassword" => $args["new_password"],
        ]);

        return true;
    }

    /**
     * Update the password of the currently authenticated user.
     *
     * @param mixed $_
     * @param array $args
     * @return bool
     */
    public function updatePassword($_, array $args): bool
    {
        $authUser = Auth::user();

        $this->authService->updatePassword([
            "currentPassword" => $args["current_password"],
            "newPassword" => $args["new_password"],
        ]);

        return true;
    }

    /**
     * Verify a user's OTP.
     *
     * @param mixed $_
     * @param array $args
     * @return bool
     * @throws GraphQLException
     */
    public function verifyUserOTP($_, array $args): bool
    {
        $userWithUuid = User::query()
            ->where("uuid", $args["user_uuid"])
            ->first();

        if (!$userWithUuid) {
            throw new GraphQLException("User with UUID not found");
        }

        $payload = [
            "userUuid" => $userWithUuid->uuid,
            "email" => $userWithUuid->email,
            "otp" => $args["otp"],
        ];

        // Free pass for now, until sending of email verification is implemented
        // $this->authService->verifyUserOtp($payload);

        return true;
    }

    /**
     * Log out the currently authenticated user.
     *
     * @param mixed $_
     * @param array $args
     * @return mixed
     */
    public function logout($_, array $args): mixed
    {
        return $this->authService->logOut();
    }

    /**
     * Delete a user.
     *
     * @param mixed $_
     * @param array $args
     * @return mixed
     * @throws GraphQLException
     */
    public function deleteUser($_, array $args): mixed
    {
        return $this->authService->deleteUser($args["id"]);
    }

    public function updateUserRole($_, array $args): bool
    {
        $payload = [
            'userUuid' => $args['uuid'],
            'roleName' => $args['role'],
        ];

        $response = $this->authService->updateUserRole($payload);

        return true;
    }
}
