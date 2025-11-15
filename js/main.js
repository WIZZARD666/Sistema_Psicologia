// Ejecuta todo el código una vez que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function () {
    // -------------------------------------------------------------------
    // Inicialización de Tooltips de Bootstrap (Instrucción 3)
    // Se verifica la existencia del objeto global 'bootstrap' para evitar el ReferenceError
    // La advertencia en consola ahora indica que DEBES cargar el script de Bootstrap primero.
    // -------------------------------------------------------------------
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            // Inicializa el tooltip para todos los elementos que lo necesiten
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    } else {
        console.warn("Advertencia: El objeto 'bootstrap' no está definido. Asegúrate de que el script de Bootstrap JS se carga *antes* de este archivo JS en el HTML.");
    }

    // -------------------------------------------------------------------
    // IMPLEMENTACIÓN: Skeleton Screen (Instrucción 2)
    // -------------------------------------------------------------------
    const skeletonLoader = document.getElementById('skeletonLoader');
    const mainContent = document.getElementById('mainContent');
    
    // 1. Mostrar skeleton screen al iniciar (Lo forzamos a 'block')
    if (skeletonLoader) {
        skeletonLoader.style.display = 'block';
    }
    
    // 2. Simular tiempo de carga
    setTimeout(function() {
        if (skeletonLoader) {
            // 3. Ocultar skeleton
            skeletonLoader.style.display = 'none';
        }
        if (mainContent) {
            // 4. Mostrar contenido real
            mainContent.style.display = 'block';
        }
        
        // Inicializar lazy loading para imágenes futuras
        initLazyLoading();
    }, 2000); // 2 segundos de simulación
});

/**
 * Genera la estructura HTML del esqueleto para la vista de Reportes.
 * Simula la disposición del formulario (inputs y tarjetas).
 * @returns {string} El HTML del esqueleto.
 */
function generateReportSkeleton() {
    return `
        <div class="skeleton-container" id="skeletonLoaderReports">
            
            <div class="skeleton-row-inputs">
                <div class="skeleton-input-group">
                    <div class="skeleton-label"></div>
                    <div class="skeleton-select"></div>
                </div>
                <div class="skeleton-input-group">
                    <div class="skeleton-label"></div>
                    <div class="skeleton-select"></div>
                </div>
            </div>

            <div class="text-center">
                <div class="skeleton-h4"></div>
            </div>

            <div class="skeleton-cards-row">
                <div class="skeleton-card"></div>
                <div class="skeleton-card"></div>
            </div>

            <div class="text-center">
                <div class="skeleton-button-center"></div>
            </div>
            
        </div>
    `;
}

        
// IMPLEMENTACIÓN: Lazy Loading (Instrucción 1A)
/* * INSTRUCCIÓN 1A: Lazy Loading con Intersection Observer
 * - Utiliza Intersection Observer API para carga diferida
 * - Control fino sobre cuándo cargar los recursos
 * - Fallback para navegadores que no soportan la API
 */
function initLazyLoading() {
    // Seleccionar todas las imágenes con data-src (para lazy loading)
    const lazyImages = document.querySelectorAll('img[data-src]');
    
    // Verificar si el navegador soporta Intersection Observer
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    // Cargar la imagen real
                    img.src = img.dataset.src;
                    img.classList.remove('lazy-image');
                    img.classList.add('loaded');
                    // Dejar de observar la imagen ya cargada
                    imageObserver.unobserve(img);
                }
            });
        });
        
        // Observar cada imagen para lazy loading
        lazyImages.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback para navegadores antiguos: cargar todas las imágenes inmediatamente
        lazyImages.forEach(img => {
            img.src = img.dataset.src;
            img.classList.remove('lazy-image');
            img.classList.add('loaded');
        });
    }
}

        
// IMPLEMENTACIÓN: Previews LQIP (Instrucción 1B)
/* * INSTRUCCIÓN 1B: Previews (Placeholders Inteligentes)
 * - Implementa técnica LQIP (Low-Quality Image Placeholder)
 * - Carga primero versión de baja calidad con blur
 * - Transición suave a imagen final de alta calidad
 */
function loadImageWithPreview(imgElement, previewSrc, finalSrc) {
    // Primero cargar la preview de baja calidad
    imgElement.src = previewSrc;
    imgElement.classList.add('lqip-image'); // Aplicar efecto blur
    
    // Luego cargar la imagen final en segundo plano
    const finalImage = new Image();
    finalImage.onload = function() {
        // Reemplazar con imagen final y quitar blur
        imgElement.src = finalSrc;
        imgElement.classList.add('loaded');
    };
    finalImage.src = finalSrc;
}

        

// Duración del mensaje motivacional en milisegundos (7 segundos)
const MOTIVATION_DURATION = 7000;
let motivationTimeout; // Variable global para manejar el timeout

// Mensajes Motivacionales AUMENTADOS
const messages = [
    "🧠 ¡Hoy es un gran día para aprender algo nuevo y desafiar tus límites mentales!",
    "🌱 Recuerda: cada pequeño avance cuenta en la construcción de tu bienestar emocional.",
    "🌟 ¡Sigue adelante, tu esfuerzo vale la pena! Eres más fuerte de lo que crees.",
    "🔑 La constancia es la clave del éxito. Un paso a la vez, con plena consciencia.",
    "💖 ¡Confía en ti! Eres la persona más importante en este proceso de crecimiento.",
    "💡 El futuro pertenece a quienes creen en la belleza de sus sueños y trabajan por ellos.",
    "🧘 Tómate un momento para respirar. Un pequeño descanso puede renovar toda tu perspectiva.",
    "☀️ No olvides celebrar tus pequeñas victorias. El progreso no siempre es lineal.",
    "💫 La mejor forma de predecir el futuro es creándolo. ¡Hoy es el primer día!",
    "🏞️ El camino es tan importante como la meta. Disfruta del proceso y aprende de él.",
    "🧭 Eres el capitán de tu alma y el dueño de tu destino. ¡Navega con propósito!",
    "🌈 Después de la tormenta, siempre sale el arcoíris. Mantén la esperanza."
];

function showMotivation() {
    // Limpiar cualquier timeout previo para evitar bugs si se pulsa rápido
    if (motivationTimeout) {
        clearTimeout(motivationTimeout);
    }

    const msg = messages[Math.floor(Math.random() * messages.length)];
    const container = document.getElementById('motivational-message-container');
    
    // 1. Ocultar y limpiar el mensaje anterior si existe
    const existingBox = document.getElementById('motivation-box');
    if (existingBox) {
        existingBox.classList.remove('motivation-visible');
        // Esperar que termine la animación de salida para removerlo
        setTimeout(() => {
            existingBox.remove();
            // Una vez removido, inyectar el nuevo
            injectNewMotivation(container, msg);
        }, 500); 
    } else {
        injectNewMotivation(container, msg);
    }
}

function injectNewMotivation(container, msg) {
    // 2. Mostrar la nueva caja motivacional
    container.innerHTML = `
        <div class="motivation-card" id="motivation-box">
            <i class="bi bi-lightbulb-fill motivation-icon-large"></i>
            <span class="text-start">${msg}</span>
        </div>
    `;
    
    // 3. Forzar el reflow para aplicar la animación
    const newBox = document.getElementById('motivation-box');
    void newBox.offsetWidth;
    
    // 4. Activar la animación
    newBox.classList.add('motivation-visible');

    // 5. Quitar la caja después de 7 segundos
    motivationTimeout = setTimeout(() => {
        // Verificar que la caja aún existe antes de intentar removerla
        const boxToRemove = document.getElementById('motivation-box');
        if (boxToRemove) {
            boxToRemove.classList.remove('motivation-visible');
            // Esperar que termine la animación de salida para removerlo
            setTimeout(() => {
                boxToRemove.remove();
            }, 500); 
        }
    }, MOTIVATION_DURATION);
}

// Mini juego de emociones
function renderMoodGame() {
    return `
        <button onclick="closeMiniGame()" style="position:absolute; top:10px; right:15px; background:none; border:none; font-size:1.5rem; color:#888; cursor:pointer; transition:color 0.2s;" onmouseover="this.style.color='var(--color4)'" onmouseout="this.style.color='#888'">&times;</button>
        <h4 class="mb-3 game-title">Mini juego: ¿Cómo te sientes?</h4>
        <p class="mb-3" style="font-size:1.1rem; color:var(--color5);">Selecciona el emoji que mejor representa tu estado de ánimo:</p>
        <div id="emojiChoices" style="font-size:2.5rem; display:flex; gap:1.2rem; justify-content:center; margin-bottom:1.5rem;">
            <span onclick="selectMood('😊', this)" style="cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">😊</span>
            <span onclick="selectMood('😐', this)" style="cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">😐</span>
            <span onclick="selectMood('😔', this)" style="cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">😔</span>
            <span onclick="selectMood('😡', this)" style="cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">😡</span>
            <span onclick="selectMood('😱', this)" style="cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">😱</span>
        </div>
        <div id="moodResult" style="min-height:2rem; text-align:center; font-weight:600; color:var(--color5);"></div>
    `;        
}

// Mini juego: trivia psicológica
const triviaQuestions = [
    {
        pregunta: "¿Qué es la resiliencia?",
        opciones: [
            "Capacidad de aprender rápido",
            "Capacidad de adaptarse y superar la adversidad",
            "Tener buena memoria"
        ],
        correcta: 1,
        explicacion: "La resiliencia es la capacidad de adaptarse y superar situaciones difíciles."
    },
    {
        pregunta: "¿Cuál es una emoción básica?",
        opciones: [
            "Orgullo",
            "Envidia",
            "Miedo"
        ],
        correcta: 2,
        explicacion: "El miedo es una de las emociones básicas universales."
    },
    {
        pregunta: "¿Qué profesional trata los trastornos mentales?",
        opciones: [
            "Psicólogo",
            "Cardiólogo",
            "Odontólogo"
        ],
        correcta: 0,
        explicacion: "El psicólogo es el profesional especializado en salud mental."
    }
];
function renderTriviaGame() {
    const q = triviaQuestions[Math.floor(Math.random() * triviaQuestions.length)];
    let opcionesHtml = "";
    q.opciones.forEach((op, idx) => {
        opcionesHtml += `<button class="btn w-100 mb-2" 
            style="background: var(--color1); border-color: var(--color5); color: var(--color5); transition: all 0.2s;" 
            onmouseover="this.style.background='var(--color5)'; this.style.color='white'"
            onmouseout="this.style.background='var(--color1)'; this.style.color='var(--color5)'"
            onclick="selectTrivia(${idx},${q.correcta},'${q.explicacion.replace(/'/g,"\\'")}', this)">${op}</button>`;
    });
    return `
        <button onclick="closeMiniGame()" style="position:absolute; top:10px; right:15px; background:none; border:none; font-size:1.5rem; color:#888; cursor:pointer; transition:color 0.2s;" onmouseover="this.style.color='var(--color4)'" onmouseout="this.style.color='#888'">&times;</button>
        <h4 class="mb-3 game-title">Mini juego: Trivia psicológica</h4>
        <p class="mb-2" style="font-size:1.1rem; color:var(--color5);">${q.pregunta}</p>
        <div id="triviaOptions">${opcionesHtml}</div>
        <div id="triviaResult" style="min-height:2rem; text-align:center; font-weight:600; margin-top:1rem;"></div>
    `;        
}
function selectTrivia(idx, correcta, explicacion, element) {
    const result = document.getElementById('triviaResult');
    const options = document.getElementById('triviaOptions').querySelectorAll('button');

    // Deshabilitar todos los botones
    options.forEach(btn => btn.disabled = true);

    if (idx === correcta) {
        // Color de éxito (verde)
        element.style.cssText += 'background: #d4edda; border-color: #c3e6cb; color: #155724;';
        result.innerHTML = `
            <span style="color:#1abc9c;">✅ ¡Correcto!</span>
            <br><span style='font-size:0.95em; color:var(--color5);'>${explicacion}</span>
        `;
    } else {
        // Color de error (rojo)
        element.style.cssText += 'background: #f8d7da; border-color: #f5c6cb; color: #721c24;';
        // Resaltar la respuesta correcta (se usa color4 para destacar la corrección)
        options[correcta].style.cssText += `background: var(--color4); border-color: var(--color4); color: white; opacity: 0.8;`;
        
        result.innerHTML = `
            <span style="color:#e74c3c;">❌ Incorrecto.</span>
            <br><span style='font-size:0.95em; color:var(--color5);'>${explicacion}</span>
        `;
    }
}

// Abrir mini juego al azar
function openMiniGame() {
    const modal = document.getElementById('miniGameModal');
    const content = document.getElementById('miniGameContent');
    if (Math.random() < 0.5) {
        content.innerHTML = renderMoodGame();
    } else {
        content.innerHTML = renderTriviaGame();
    }
    modal.style.display = 'flex';
    // Usa la clase para activar la animación de entrada
    setTimeout(() => modal.classList.add('show'), 10);
}
function closeMiniGame() {
    const modal = document.getElementById('miniGameModal');
    // Usa la clase para activar la animación de salida
    modal.classList.remove('show');
    setTimeout(() => modal.style.display = 'none', 300); // Ocultar después de la transición
}
function selectMood(emoji, element) {
    let message = "";
    
    // Remover borde de selección previa
    document.querySelectorAll('#emojiChoices span').forEach(span => {
        span.style.border = 'none';
    });
    // Añadir borde de selección actual
    element.style.border = '3px solid var(--color4)';
    element.style.borderRadius = '50%';
    
    switch(emoji) {
        case "😊": message = "¡Genial! Sigue así y comparte tu alegría. "; break;
        case "😐": message = "Recuerda que está bien tener días neutros. ¡Ánimo! "; break;
        case "😔": message = "Si te sientes triste, habla con alguien de confianza. "; break;
        case "😡": message = "Respira profundo, identifica la causa y busca calmarte. "; break;
        case "😱": message = "La ansiedad es normal, intenta relajarte y pedir ayuda si lo necesitas. "; break;
    }
    document.getElementById('moodResult').innerHTML = emoji + " " + message;
}
// Cierra el modal si se hace click fuera del cuadro
window.onclick = function(event) {
    const modal = document.getElementById('miniGameModal');
    if (event.target === modal && modal.classList.contains('show')) {
        closeMiniGame();
    }
}