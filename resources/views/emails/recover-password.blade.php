<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha - SimHub</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f7fa;
            padding: 20px;
            line-height: 1.6;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #63c0c6;
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
        }

        .message {
            font-size: 15px;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.7;
        }

        .code-container {
            background: #f8f9fa;
            border: 2px dashed #63c0c6;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }

        .code-label {
            font-size: 13px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .code {
            font-size: 42px;
            font-weight: bold;
            color: #63c0c6;
            letter-spacing: 12px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
        }

        .code-info {
            font-size: 13px;
            color: #999;
            margin-top: 12px;
        }

        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
        }

        .warning-title {
            font-weight: 600;
            color: #856404;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .warning-text {
            color: #856404;
            font-size: 13px;
        }

        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
        }

        .info-text {
            color: #0d47a1;
            font-size: 13px;
        }

        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .footer-title {
            font-size: 14px;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .footer-text {
            font-size: 12px;
            color: #999;
            margin: 5px 0;
        }

        .footer-link {
            color: #63c0c6;
            text-decoration: none;
        }

        .footer-link:hover {
            text-decoration: underline;
        }

        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }

            .header {
                padding: 30px 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .content {
                padding: 30px 20px;
            }

            .code {
                font-size: 36px;
                letter-spacing: 8px;
            }
        }
    </style>
</head>
<body>
<div class="email-container">
    <!-- Header -->
    <div class="header">
        <h1>Recuperação de Senha</h1>
        <p>SOLUÇÕES INTEGRADAS DE MONITORAMENTO</p>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="greeting">
            @if($userName)
                Olá, {{ $userName }}!
            @else
                Olá!
            @endif
        </div>

        <div class="message">
            Recebemos uma solicitação para redefinir a senha da sua conta no <strong>SimHub</strong>.
        </div>

        <div class="message">
            Use o código abaixo para criar uma nova senha:
        </div>

        <!-- Código -->
        <div class="code-container">
            <div class="code-label">Seu código de verificação</div>
            <div class="code">{{ $code }}</div>
            <div class="code-info">Digite este código na tela de recuperação</div>
        </div>

        <!-- Informação de segurança -->
        <div class="info-box">
            <div class="info-text">
                <strong>Segurança:</strong> Se você não solicitou esta recuperação de senha, ignore este email.
                Sua senha permanecerá inalterada e ninguém terá acesso à sua conta.
            </div>
        </div>

        <div class="message">
            Se tiver alguma dúvida ou problema, entre em contato com nosso suporte.
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-title">SimHub</div>
        <div class="footer-text"></div>
        <div class="footer-text">
            <a href="https://controle.simhub.com.br" class="footer-link">controle.simhub.com.br</a>
        </div>
        <div class="footer-text" style="margin-top: 15px; color: #ccc;">
            Este é um email automático. Por favor, não responda.
        </div>
    </div>
</div>
</body>
</html>
