<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nota compartida</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1a1a2e; color: #fff; padding: 20px; border-radius: 8px; text-align: center; }
        .content { padding: 20px; border: 1px solid #ddd; border-radius: 8px; margin-top: 20px; }
        .button { display: inline-block; background: #d4af37; color: #000; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin-top: 20px; font-weight: bold; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Grimorio</h1>
            <p>Una nota ha sido compartida contigo</p>
        </div>
        
        <div class="content">
            <h2>{{ $note->title }}</h2>
            
            @if($note->description)
                <p><strong>Descripción:</strong> {{ $note->description }}</p>
            @endif
            
            <p><strong>Acceso:</strong> 
                @if($accessLevel === 'read')
                    Lectura (solo ver)
                @else
                    Edición (puedes editar)
                @endif
            </p>
            
            <p>Haz click en el botón para ver la nota:</p>
            
            <center>
                <a href="{{ $url }}" class="button">Ver nota</a>
            </center>
            
            <p style="font-size: 12px; color: #999; margin-top: 20px;">
                O copia este enlace en tu navegador:<br>
                <code>{{ $url }}</code>
            </p>
        </div>
        
        <div class="footer">
            <p>© 2026 Grimorio. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
