<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - Ver Vista de Abrir Caja</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #1f2937;
            color: white;
        }
        .info-box {
            background: #374151;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: #7c3aed;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #6d28d9;
        }
        code {
            background: #1f2937;
            padding: 2px 6px;
            border-radius: 4px;
            color: #a5f3fc;
        }
    </style>
</head>
<body>
    <h1>🔍 Verificación del Sistema de Cajeros</h1>

    <div class="info-box">
        <h2>📋 Deberías ver un BOTÓN MORADO</h2>
        <p>En la página de "Abrir Caja" debe aparecer:</p>
        <ol>
            <li>Un select que dice "Sin cajero asignado"</li>
            <li>Un <strong>botón morado grande</strong> que dice "👤 Agregar Nuevo Cajero"</li>
            <li>Un texto de ayuda debajo</li>
        </ol>
    </div>

    <div style="text-align: center; margin: 40px 0;">
        <a href="/caja/abrir" class="btn">🔓 Ir a Abrir Caja</a>
        <a href="/cajeros" class="btn">👥 Ir a Gestión de Cajeros</a>
    </div>

    <div class="info-box">
        <h2>🤔 ¿No ves el botón?</h2>
        <ol>
            <li>Presiona <code>Ctrl + Shift + R</code> (o <code>Cmd + Shift + R</code> en Mac)</li>
            <li>Verifica que estés en <code>/caja/abrir</code></li>
            <li>Prueba en modo incógnito del navegador</li>
        </ol>
    </div>
</body>
</html>
