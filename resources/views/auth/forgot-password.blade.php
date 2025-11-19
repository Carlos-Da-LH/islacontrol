<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* TOAST ESTILO MODERNO Y CENTRADO */
        .toast {
            position: fixed;
            top: 25px;
            left: 50%;
            transform: translateX(-50%) translateY(-20px);
            background-color: #5F9E74;
            color: white;
            padding: 14px 22px;
            border-radius: 10px;
            font-size: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            opacity: 0;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 999999;
            min-width: 260px;
            justify-content: center;
            text-align: center;
        }

        .toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        .toast.error {
            background-color: #e63946;
        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen font-sans">

    <!-- Contenedor principal -->
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-10">

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Recuperar contraseña
        </h2>

        <p class="text-sm text-gray-600 text-center mb-4">
            Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.
        </p>

        <!-- Formulario -->
        <form id="forgot-form" class="space-y-4">

            <input type="email" id="email" placeholder="Tu correo"
                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" />

            <button type="submit"
                class="w-full py-3 rounded-full text-white font-semibold hover:scale-105 transition-transform"
                style="background-color:#5F9E74;">
                Enviar enlace
            </button>
        </form>

        <!-- Botón volver -->
        <p class="mt-6 text-center">
            <a href="/" class="text-green-700 hover:underline font-medium">
                Volver a la pantalla inicial
            </a>
        </p>

    </div>

    <!-- TOAST (CENTRADO) -->
    <div id="toast" class="toast hidden"></div>

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js"></script>

    <script>
        // CONFIGURACIÓN DE FIREBASE
        const firebaseConfig = {
            apiKey: "AIzaSyA8VguwL3jh2lIVpBSRrOvjy-c0PfmGD-4",
            authDomain: "isla-control.firebaseapp.com",
            projectId: "isla-control",
            storageBucket: "isla-control.firebasestorage.app",
            messagingSenderId: "145410754650",
            appId: "1:145410754650:web:8d590e161d280094a6f063"
        };

        firebase.initializeApp(firebaseConfig);

        // TOAST BONITO Y CENTRADO
        function showToast(message, type = "success") {
            const toast = document.getElementById("toast");

            toast.innerHTML = `
                ${type === "success" ? "✅" : "⚠️"}
                <span>${message}</span>
            `;

            toast.classList.remove("hidden");
            toast.classList.add("show");

            if (type === "error") {
                toast.classList.add("error");
            } else {
                toast.classList.remove("error");
            }

            setTimeout(() => {
                toast.classList.remove("show");
                setTimeout(() => toast.classList.add("hidden"), 300);
            }, 3000);
        }

        // ENVÍO DEL CORREO
        document.getElementById("forgot-form").addEventListener("submit", function (e) {
            e.preventDefault();

            const email = document.getElementById("email").value.trim();

            if (email === "") {
                showToast("Escribe un correo válido", "error");
                return;
            }

            firebase.auth().sendPasswordResetEmail(email)
                .then(() => {
                    showToast("Enlace enviado. Revisa tu correo.");
                })
                .catch((err) => {
                    let msg = "Error al enviar el correo.";

                    if (err.code === "auth/user-not-found")
                        msg = "No existe una cuenta con este correo.";

                    if (err.code === "auth/invalid-email")
                        msg = "Correo inválido.";

                    showToast(msg, "error");
                });
        });
    </script>

</body>
</html>
