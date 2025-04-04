<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swagger API Documentation</title>

    <!-- Swagger UI CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/4.1.3/swagger-ui.min.css">
</head>
<body>
    <div id="swagger-ui"></div>

    <!-- Swagger UI JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/4.1.3/swagger-ui-bundle.min.js"></script>
    <script>
        const ui = SwaggerUIBundle({
            url: '{{ $swaggerURL }}', // Update this path to your Swagger JSON file location
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIBundle.SwaggerUIStandalonePreset
            ],
            layout: "BaseLayout"
        });
    </script>
</body>
</html>
