/* - 
- Mostrar en tiempo real la carga de la Image 
-  durante llenado del formulario

 */

(function () {
    const input = document.getElementById('imagenUpload');
    const preview = document.getElementById('imagenPreview');
    const clear = document.getElementById('previewClear');
    const spinner = document.getElementById('barSpinner');

    if (!input || !preview) return;

    let currentObjectUrl = null;

    function showPreview(file) {
        if (!file || !file.type.startsWith('image/')) {
            if (!file.type.startsWith('image/')) alert('No es imagen.');
            return;
        }

        if (currentObjectUrl) {
            URL.revokeObjectURL(currentObjectUrl);
            currentObjectUrl = null;
        }

        currentObjectUrl = URL.createObjectURL(file);
        preview.src = currentObjectUrl;
        preview.style.display = 'block';

        if (clear) clear.style.display = 'inline-block';
        if (spinner) spinner.style.display = 'none';
    }

    function clearPreview() {
        preview.src = '';
        preview.style.display = 'none';
        if (clear) clear.style.display = 'none';
        if (spinner) spinner.style.display = '';

        if (currentObjectUrl) {
            URL.revokeObjectURL(currentObjectUrl);
            currentObjectUrl = null;
        }

        // ✅ NO TOCAR input.value → PHP mantiene datos
    }

    input.addEventListener('change', function () {
        const file = this.files && this.files[0];
        showPreview(file);
    });

    if (clear) {
        clear.addEventListener('click', clearPreview);
    }
})();


// (function () {
//     const input = document.getElementById('imagenUpload');
//     const preview = document.getElementById('imagenPreview');
//     const clear = document.getElementById('previewClear');
//     const spinner = document.getElementById('barSpinner');

//     if (!input || !preview) return;

//     let currentObjectUrl = null;

//     function showPreview(file) {
//         // Validaciones básicas
//         if (!file) return;
//         if (!file.type.startsWith('image/')) {
//             alert('El archivo seleccionado no es una imagen.');
//             input.value = '';
//             return;
//         }
//         // if (file.size > 102400) { // 100 KB
//         //     alert('La imagen supera el límite de 100 KB.');
//         //     input.value = '';
//         //     return;
//         // }

//         // Liberar URL anterior si existe
//         if (currentObjectUrl) {
//             URL.revokeObjectURL(currentObjectUrl);
//             currentObjectUrl = null;
//         }

//         // Crear URL temporal y mostrar
//         currentObjectUrl = URL.createObjectURL(file);
//         preview.src = currentObjectUrl;
//         preview.style.display = 'block';

//         // Mostrar botón "Quitar"
//         if (clear) clear.style.display = 'inline-block';

//         // Ocultar spinner si está presente
//         if (spinner) spinner.style.display = 'none';
//     }

//     function clearPreview() {
//         // Reset visual y estado
//         preview.src = '';
//         preview.style.display = 'none';
//         if (clear) clear.style.display = 'none';
//         if (spinner) spinner.style.display = ''; // vuelve al estado por defecto del layout

//         // Revocar URL temporal
//         if (currentObjectUrl) {
//             URL.revokeObjectURL(currentObjectUrl);
//             currentObjectUrl = null;
//         }

//         // Limpiar input
//         // LEARN
//         // input.value = '';  // Causó gran problema: Dejar vacio el formulario con cada POST
//         // LEARN 
//     }

//     input.addEventListener('change', function () {
//         const file = this.files && this.files[0];
//         showPreview(file);
//     });

//     if (clear) {
//         clear.addEventListener('click', clearPreview);
//     }
// })();




/* (function () {
    document.addEventListener("DOMContentLoaded", function () {

        const input = document.getElementById('imagenUpload');
        const preview = document.getElementById('imagenPreview');

        if (!input || !preview) return; // seguridad: si no existen, no hace nada

        input.addEventListener('change', () => {
            const file = input.files[0];
            if (file) {
                // Validación de tamaño (100 kb máx.)
                if (file.size > 102400) {
                    alert('La imagen supera el límite de 100 kb.');
                    input.value = ''; // limpiar input
                    return;
                }

                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // asegurar que se muestre
                };
                reader.readAsDataURL(file);
            }

        });
    });
})();
 */