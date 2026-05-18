@extends('layouts.app')
@section('title', $note->title . ' - Grimorio')

@section('content')
<div class="grid-2" style="grid-template-columns: 2fr 1fr;">
    <div>
        <!-- Encabezado y Contenido -->
        <div class="card" style="margin-bottom: 2rem;">
            <div class="card-body">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
                    <div style="flex: 1;">
                        <h1 style="margin-bottom: 0.5rem;">{{ $note->title }}</h1>
                        @if($note->description)
                            <p style="font-size: 1.1rem; color: var(--text-secondary);">{{ $note->description }}</p>
                        @endif
                        <small style="color: var(--text-secondary);">
                            Creada {{ $note->created_at->format('d/m/Y H:i') }} · Actualizada {{ $note->updated_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; justify-content: flex-end;">
                        <a href="{{ route('notes.edit', $note) }}" class="btn-primary" style=" text-decoration:none; padding: 0.75rem 1.25rem;">Editar</a>
                        <button type="button" class="btn-primary" style="padding: 0.75rem 1.25rem; background: linear-gradient(135deg, var(--accent-gold), var(--accent-bronze)); color: var(--primary-dark);" onclick="document.getElementById('shareModal').style.display='flex'">Compartir</button>
                        <form method="POST" action="{{ route('notes.destroy', $note) }}" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-primary" style="padding: 0.75rem 1.25rem; background: linear-gradient(135deg, rgba(248, 113, 113, 0.8), rgba(244, 67, 54, 0.8)); color: white;" onclick="return confirm('¿Eliminar esta nota permanentemente?')">Eliminar</button>
                        </form>
                    </div>
                </div>

                @if($note->tags->count())
                    <div style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--glass-border);">
                        @foreach($note->tags as $tag)
                            <span class="badge">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="content-pre">{{ $note->content ?? '(Sin contenido)' }}</div>
            </div>
        </div>
    </div>

    <!-- Sidebar: Información y acciones -->
    <div>
        <!-- Estadísticas -->
        <div class="card" style="margin-bottom: 2rem;">
            <div class="card-body">
                <h3 style="margin-bottom: 1rem;">Detalles</h3>
                <div style="display: grid; gap: 1rem;">
                    <div style="border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem;">
                        <small style="color: var(--text-secondary); display: block;">Propietario</small>
                        <p style="margin: 0; color: var(--text-primary);">{{ $note->user->email }}</p>
                    </div>
                    <div style="border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem;">
                        <small style="color: var(--text-secondary); display: block;">Creada</small>
                        <p style="margin: 0; color: var(--text-primary);">{{ $note->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <small style="color: var(--text-secondary); display: block;">Actualizada</small>
                        <p style="margin: 0; color: var(--text-primary);">{{ $note->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Links de compartición activos -->
        @if($note->sharedLinks->count())
            <div class="card">
                <div class="card-body">
                    <h3 style="margin-bottom: 1rem;">Compartida con</h3>
                    @foreach($note->sharedLinks as $link)
                        <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--glass-border);">
                            @if($link->recipient)
                                <p style="margin: 0; color: var(--text-primary);">{{ $link->recipient->email }}</p>
                            @else
                                <p style="margin: 0; color: var(--text-primary); font-style: italic;">Link Público</p>
                            @endif
                            <small style="color: var(--text-secondary);">
                                Acceso: <strong>{{ ucfirst($link->access_level) }}</strong>
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal de compartición -->
<div id="shareModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 500px; max-height: 90vh; overflow-y: auto;">
        <div class="card-body">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Compartir Nota</h2>
                <button onclick="document.getElementById('shareModal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; color: var(--text-secondary); cursor: pointer;">×</button>
            </div>

            <!-- TAB 1: Link Copiable -->
            <div id="tab-link" style="display: block;">
                <h3 style="margin-bottom: 1rem; color: var(--accent-gold);"> Link Copiable</h3>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem; font-size: 0.95rem;">
                    Genera un enlace que puedes copiar y compartir por WhatsApp, email, redes sociales, etc.
                </p>

                <form method="POST" action="{{ route('shared.store', $note) }}" id="form-link">
                    @csrf
                    <input type="hidden" name="share_type" value="link">
                    
                    <div class="form-group">
                        <label>Nivel de acceso</label>
                        <select name="access_level" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 2px solid var(--glass-border); background: var(--glass-bg); color: var(--text-primary); margin-bottom: 1.5rem;">
                            <option value="read"> Lectura (solo ver sin login)</option>
                            <option value="edit"> Edición (requiere login para editar)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-primary" style="width: 100%; padding: 0.75rem;">🔗 Generar Link</button>
                </form>

                <div id="link-result" style="display: none; margin-top: 1.5rem; padding: 1rem; background: rgba(212, 175, 55, 0.1); border-radius: 8px; border: 1px solid var(--accent-gold);">
                    <small style="color: var(--text-secondary); display: block; margin-bottom: 0.5rem;">Tu enlace:</small>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" id="link-url" readonly 
                            style="flex: 1; padding: 0.75rem; border-radius: 6px; background: var(--primary-dark); color: var(--accent-gold); border: 1px solid var(--glass-border); font-size: 0.85rem; word-break: break-all;">
                        <button type="button" onclick="copyToClipboard(document.getElementById('link-url').value)" 
                            class="btn-primary" style="padding: 0.75rem 1rem; white-space: nowrap;">📋 Copiar</button>
                    </div>
                </div>

                @if(session('share_link'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            document.getElementById('link-url').value = '{{ session('share_link') }}';
                            document.getElementById('link-result').style.display = 'block';
                        });
                    </script>
                @endif
            </div>

            <!-- Separador -->
            <div style="height: 1px; background: var(--glass-border); margin: 2rem 0;"></div>

            <!-- TAB 2: Compartir por Email -->
            <div id="tab-email">
                <h3 style="margin-bottom: 1rem; color: var(--accent-gold);"> Compartir por Email</h3>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem; font-size: 0.95rem;">
                    Si la persona aún no está registrada, recibirá una invitación automática.
                </p>

                <form method="POST" action="{{ route('shared.store', $note) }}" id="form-email">
                    @csrf
                    <input type="hidden" name="share_type" value="email">
                    
                    <div class="form-group">
                        <label>Email del destinatario</label>
                        <input type="email" name="recipient_email" 
                            style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 2px solid var(--glass-border); background: var(--glass-bg); color: var(--text-primary); margin-bottom: 1rem;">
                        @error('recipient_email') <small style="color: #f87171;">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label>Nivel de acceso</label>
                        <select name="access_level" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 2px solid var(--glass-border); background: var(--glass-bg); color: var(--text-primary); margin-bottom: 1.5rem;">
                            <option value="read"> Lectura</option>
                            <option value="edit"> Edición</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-primary" style="width: 100%; padding: 0.75rem;">📤 Enviar Invitación</button>
                </form>

                @error('share') <p style="color: #f87171; margin-top: 1rem;">{{ $message }}</p> @enderror
            </div>

            @if(session('success'))
                <div style="margin-top: 1.5rem; padding: 1rem; background: rgba(52, 211, 153, 0.1); border-radius: 8px; border: 1px solid #34d399; color: #10b981;">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Enlace copiado al portapapeles');
    });
}

// AJAX para el formulario de link
document.addEventListener('DOMContentLoaded', function() {
    const formLink = document.getElementById('form-link');
    if (formLink) {
        formLink.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            fetch('{{ route('shared.store', $note) }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.share_link) {
                    document.getElementById('link-url').value = data.share_link;
                    document.getElementById('link-result').style.display = 'block';
                    // Limpiar el input de email si estaba lleno
                    document.querySelector('input[name="recipient_email"]').value = '';
                }
                if (data.success) {
                    console.log(data.success);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});
</script>

@endsection
