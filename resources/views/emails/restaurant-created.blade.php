<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Activada</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 30px;
            border-bottom: 3px solid #696cff;
        }
        .header h1 {
            color: #696cff;
            margin: 0;
            font-size: 28px;
        }
        .welcome-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        .content {
            padding: 30px 0;
        }
        .credentials-box {
            background-color: #f8f9fa;
            border-left: 4px solid #696cff;
            padding: 20px;
            margin: 25px 0;
            border-radius: 5px;
        }
        .credentials-box h3 {
            margin-top: 0;
            color: #696cff;
            font-size: 18px;
        }
        .credential-item {
            margin: 15px 0;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
        }
        .credential-label {
            font-weight: bold;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .credential-value {
            font-size: 16px;
            color: #333;
            margin-top: 5px;
            word-break: break-all;
        }
        .button {
            display: inline-block;
            padding: 15px 40px;
            background-color: #696cff;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #5f61e6;
        }
        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .success-box {
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #e0e0e0;
            color: #666;
            font-size: 14px;
        }
        .features {
            margin: 30px 0;
        }
        .feature-item {
            padding: 10px 0;
            display: flex;
            align-items: center;
        }
        .feature-icon {
            color: #4caf50;
            margin-right: 10px;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="welcome-icon">🎉</div>
            <h1>¡Bienvenido a {{ $companyName }}!</h1>
            <p style="color: #666; margin-top: 10px;">Tu cuenta ha sido activada exitosamente</p>
        </div>

        <div class="content">
            <p>Hola <strong>{{ $adminName }}</strong>,</p>

            <div class="success-box">
                <strong>✅ ¡Excelente noticia!</strong><br>
                Tu restaurante <strong>{{ $restaurantName }}</strong> ha sido configurado exitosamente en nuestro sistema.
            </div>

            <p>{{ $welcomeMessage }}</p>

            <div class="credentials-box">
                <h3>🔐 Tus Credenciales de Acceso</h3>

                <div class="credential-item">
                    <div class="credential-label">URL de Acceso</div>
                    <div class="credential-value">
                        <a href="{{ $loginUrl }}" style="color: #696cff;">{{ $loginUrl }}</a>
                    </div>
                </div>

                <div class="credential-item">
                    <div class="credential-label">Email</div>
                    <div class="credential-value">{{ $adminEmail }}</div>
                </div>

                <div class="credential-item">
                    <div class="credential-label">Contraseña</div>
                    <div class="credential-value">{{ $adminPassword }}</div>
                </div>
            </div>

            @if($trialDays > 0)
            <div class="info-box">
                <strong>🎁 Período de Prueba</strong><br>
                Tienes <strong>{{ $trialDays }} días</strong> de prueba gratuita para explorar todas las funcionalidades del plan <strong>{{ $planName }}</strong>.
            </div>
            @else
            <div class="info-box">
                <strong>📦 Plan Activo</strong><br>
                Tu plan <strong>{{ $planName }}</strong> está activo y listo para usar.
            </div>
            @endif

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $loginUrl }}" class="button">Acceder al Sistema</a>
            </div>

            <div class="features">
                <h3 style="color: #696cff;">✨ Lo que puedes hacer ahora:</h3>

                <div class="feature-item">
                    <span class="feature-icon">✓</span>
                    <span>Gestionar productos y categorías de tu menú</span>
                </div>

                <div class="feature-item">
                    <span class="feature-icon">✓</span>
                    <span>Configurar mesas y zonas de tu restaurante</span>
                </div>

                <div class="feature-item">
                    <span class="feature-icon">✓</span>
                    <span>Recibir pedidos online y para delivery</span>
                </div>

                <div class="feature-item">
                    <span class="feature-icon">✓</span>
                    <span>Usar el sistema POS para atención en mesas</span>
                </div>

                <div class="feature-item">
                    <span class="feature-icon">✓</span>
                    <span>Ver estadísticas y reportes en tiempo real</span>
                </div>

                <div class="feature-item">
                    <span class="feature-icon">✓</span>
                    <span>Gestionar usuarios y permisos del personal</span>
                </div>
            </div>

            <div class="info-box">
                <strong>💡 Consejo de Seguridad</strong><br>
                Por tu seguridad, te recomendamos cambiar tu contraseña después del primer inicio de sesión. Puedes hacerlo desde la sección de "Ajustes" en tu panel de control.
            </div>

            <p>Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos en <a href="mailto:{{ $supportEmail }}" style="color: #696cff;">{{ $supportEmail }}</a>. Estamos aquí para ayudarte a tener éxito.</p>

            <p style="margin-top: 30px;">
                Saludos cordiales,<br>
                <strong>El Equipo de {{ $companyName }}</strong>
            </p>
        </div>

        <div class="footer">
            <p>{{ $footerText }}</p>
            <p style="margin-top: 10px;">© {{ date('Y') }} {{ $companyName }}. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
