<!-- MODAL DE LOGIN - DISEÑO NEÓN GLASSMORPHISM -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content login-modal-content">
            
            <!-- Botón de cerrar (X) -->
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-4" data-bs-dismiss="modal" aria-label="Close" style="z-index: 10;"></button>

            <div class="modal-body p-5">
                <!-- Logo y Título -->
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo_fest.png') }}" alt="DEV FEST Logo" style="width: 80px; filter: drop-shadow(0 0 15px rgba(147, 51, 234, 0.8));" class="mb-3 floating-logo">
                    <h3 class="text-white fw-bold" style="font-family: 'Outfit', sans-serif;">Acceso Administrativo</h3>
                    <p class="text-sand small">Ingresa tus credenciales para gestionar el evento.</p>
                </div>

                <!-- Formulario -->
                <form method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf
                    
                    <!-- Campo Email -->
                    <div class="mb-4 input-group-custom">
                        <label for="email" class="form-label text-sand small fw-bold">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0" style="border-color: rgba(192, 132, 252, 0.4);">
                                <i class="fa-solid fa-envelope" style="color: var(--color-terracotta);"></i>
                            </span>
                            <input type="email" class="form-control bg-transparent border-start-0 text-white custom-input" id="email" name="email" placeholder="admin@devfest.com" required autofocus>
                        </div>
                    </div>

                    <!-- Campo Contraseña -->
                    <div class="mb-4 input-group-custom">
                        <label for="password" class="form-label text-sand small fw-bold">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0" style="border-color: rgba(192, 132, 252, 0.4);">
                                <i class="fa-solid fa-lock" style="color: var(--color-terracotta);"></i>
                            </span>
                            <input type="password" class="form-control bg-transparent border-start-0 text-white custom-input" id="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <!-- Botón Submit -->
                    <button type="submit" class="btn btn-neon w-100 py-3 mt-2">
                        Ingresar al Sistema <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
                    </button>
                </form>

                <!-- Mensaje de Error (Laravel Validation) -->
                @if ($errors->any())
                    <div class="alert alert-danger mt-3 bg-transparent border-danger text-danger small">
                        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ $errors->first() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ESTILOS EXCLUSIVOS PARA EL MODAL -->
<style>
    /* Fondo oscuro con blur para el modal */
    .modal-backdrop.show {
        opacity: 0.8;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        background-color: #050208;
    }

    /* Contenedor del modal con efecto cristal */
    .login-modal-content {
        background: linear-gradient(165deg, rgba(20, 10, 35, 0.95), rgba(5, 2, 8, 0.98));
        border: 1px solid rgba(147, 51, 234, 0.4);
        border-radius: 24px;
        box-shadow: 
            0 0 50px rgba(147, 51, 234, 0.3),
            inset 0 0 30px rgba(147, 51, 234, 0.1);
        overflow: hidden;
        position: relative;
    }

    /* Brillo superior decorativo */
    .login-modal-content::before {
        content: '';
        position: absolute;
        top: 0; left: 10%; right: 10%;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--color-sand), transparent);
        box-shadow: 0 0 20px var(--color-terracotta);
    }

    /* Inputs personalizados */
    .custom-input {
        border-color: rgba(192, 132, 252, 0.4) !important;
        color: #fff !important;
        transition: all 0.3s ease;
    }
    .custom-input:focus {
        background-color: rgba(147, 51, 234, 0.1) !important;
        border-color: var(--color-terracotta) !important;
        box-shadow: 0 0 15px rgba(147, 51, 234, 0.4) !important;
    }
    .custom-input::placeholder {
        color: rgba(255, 255, 255, 0.3);
    }

    /* Animación de flotación para el logo del modal */
    .floating-logo {
        animation: floatLogo 4s ease-in-out infinite;
    }
    @keyframes floatLogo {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    /* Animación de entrada del modal (Zoom in) */
    .modal.fade .modal-dialog {
        transform: scale(0.8) translateY(20px);
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .modal.show .modal-dialog {
        transform: scale(1) translateY(0);
    }
</style>