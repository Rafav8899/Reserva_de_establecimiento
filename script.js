/* =====================================
    CONFIGURACIÓN Y VARIABLES
===================================== */
const daysContainer = document.getElementById("days");
const monthYear = document.getElementById("monthYear");
const prevBtn = document.getElementById("prev");
const nextBtn = document.getElementById("next");
const inputFechaHidden = document.getElementById("fechaSeleccionada");
const inputTurno = document.getElementById("turno_reserva");

const meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

let fechaActual = new Date();
let fechasOcupadas = []; // Acá guardamos lo que traiga el PHP

/* =====================================
    CARGA DE DATOS (AJAX)
===================================== */

// Función para pedir disponibilidad al servidor
async function cargarDisponibilidad() {
    const turnoSeleccionado = inputTurno.value; // Puede estar vacío o tener "mañana"/"tarde"
    
    try {
        // Le pedimos al PHP las fechas ocupadas (opcionalmente filtradas por turno)
        const res = await fetch(`fechas_ocupadas.php?turno=${turnoSeleccionado}`);
        fechasOcupadas = await res.json();
        
        // Una vez que tenemos los datos, dibujamos el calendario
        renderizarCalendario();
    } catch (err) {
        console.error('Error cargando disponibilidad:', err);
        renderizarCalendario(); // Dibujamos igual aunque falle la red
    }
}

/* =====================================
    LÓGICA DEL CALENDARIO (DIBUJO)
===================================== */

function renderizarCalendario() {
    const year = fechaActual.getFullYear();
    const month = fechaActual.getMonth();
    
    monthYear.textContent = `${meses[month]} ${year}`;
    daysContainer.innerHTML = "";

    const hoy = new Date();
    hoy.setHours(0,0,0,0);

    // Cálculos de días
    const primerDiaIndex = new Date(year, month, 1).getDay();
    const totalDiasMes = new Date(year, month + 1, 0).getDate();

    // 1. Crear espacios vacíos para el inicio del mes
    for(let i = 0; i < primerDiaIndex; i++) {
        daysContainer.appendChild(document.createElement("div"));
    }

    // 2. Crear los días del mes
    for(let dia = 1; dia <= totalDiasMes; dia++) {
        // Creamos objeto fecha para el día actual del bucle
        const fechaObj = new Date(year, month, dia);
        
        // Formatear a YYYY-MM-DD (Manual para evitar errores de zona horaria)
        const y = fechaObj.getFullYear();
        const m = String(fechaObj.getMonth() + 1).padStart(2, '0');
        const d = String(fechaObj.getDate()).padStart(2, '0');
        const fechaStr = `${y}-${m}-${d}`;
        
        const diaSemana = fechaObj.getDay();
        
        // Crear el elemento visual
        const divDia = document.createElement("div");
        divDia.textContent = dia;
        divDia.classList.add("dia");
        divDia.dataset.fecha = fechaStr;

        // --- REGLAS DE BLOQUEO ---

        // A. Fines de semana
        if(diaSemana === 0 || diaSemana === 6) {
            divDia.classList.add("disabled");
        } 
        // B. Días pasados
        else if(fechaObj < hoy) {
            divDia.classList.add("past");
        } 
        // C. Lógica de Disponibilidad según Base de Datos
        else {
            const turnoElegido = inputTurno.value;
            let ocupado = false;

            if (turnoElegido !== "") {
                // Si el usuario eligió un turno, bloqueamos si ESE turno está ocupado
                ocupado = fechasOcupadas.some(r => r.fecha === fechaStr);
            } else {
                // Si no hay turno elegido, bloqueamos solo si el día está totalmente lleno (ej: 2 turnos)
                const cantidadTurnos = fechasOcupadas.filter(r => r.fecha === fechaStr).length;
                if(cantidadTurnos >= 2) ocupado = true;
            }

            if (ocupado) {
                divDia.classList.add("ocupado");
                // No le asignamos onclick porque está ocupado
            } else {
                divDia.classList.add("available");
                divDia.onclick = () => seleccionarDia(divDia);
            }
        }

        // D. Mantener la selección visual si el usuario cambia de mes y vuelve
        if (inputFechaHidden.value === fechaStr && !divDia.classList.contains("ocupado")) {
            divDia.classList.add("seleccionada");
        }

        daysContainer.appendChild(divDia);
    }
}

/* =====================================
    INTERACCIONES DEL USUARIO
===================================== */

function seleccionarDia(elemento) {
    // Quitar clase seleccionada de todos
    document.querySelectorAll(".dia").forEach(d => d.classList.remove("seleccionada"));
    
    // Marcar el actual
    elemento.classList.add("seleccionada");
    
    // Guardar el valor para el formulario
    inputFechaHidden.value = elemento.dataset.fecha;
    console.log("Fecha seleccionada:", inputFechaHidden.value);
}

// Cuando el usuario cambia el turno, refrescamos los datos y el calendario
inputTurno.addEventListener('change', () => {
    cargarDisponibilidad();
});

// Navegación de meses
prevBtn.addEventListener("click", () => {
    fechaActual.setMonth(fechaActual.getMonth() - 1);
    renderizarCalendario(); 
});

nextBtn.addEventListener("click", () => {
    fechaActual.setMonth(fechaActual.getMonth() + 1);
    renderizarCalendario();
});


/* =====================================
    AUTOCOMPLETADO POR DNI
===================================== */
const inputDni = document.querySelector('input[name="dni"]');

inputDni.addEventListener('blur', async () => {
    const dniVal = inputDni.value.trim();

    // Solo buscamos si el DNI tiene una longitud razonable (ej: más de 6 dígitos)
    if (dniVal.length > 6) {
        try {
            const response = await fetch(`buscar_cliente.php?dni=${dniVal}`);
            const resultado = await response.json();

            if (resultado.existe) {
                console.log("Cliente encontrado. Autocompletando...");
                
                // Mapeamos los datos a los inputs correspondientes
                // Importante: Chequeá que los names de tus inputs coincidan
                document.querySelector('input[name="nombre"]').value = resultado.datos.nombre;
                document.querySelector('input[name="apellido"]').value = resultado.datos.apellido;
                document.querySelector('input[name="gmail"]').value = resultado.datos.gmail;
                document.querySelector('input[name="celular"]').value = resultado.datos.celular;
                document.querySelector('input[name="domicilio"]').value = resultado.datos.domicilio;
                document.querySelector('input[name="residencia"]').value = resultado.datos.residencia;
                document.querySelector('input[name="nacionalidad"]').value = resultado.datos.nacionalidad;

                // Sinceridad: Si el cliente ya existe, podés ocultar la subida de fotos del DNI
                const seccionFotos = document.getElementById('seccion-dni'); // Si tenés un ID en ese div
                const inputFrente = document.querySelector('input[name="dni_frente"]');
                const inputDorso = document.querySelector('input[name="dni_dorso"]');
                if (seccionFotos) {
                    seccionFotos.style.display = 'none';
                    // Quitamos el required para que no de error al enviar
                    document.querySelector('input[name="dni_frente"]').required = false;
                    document.querySelector('input[name="dni_dorso"]').required = false;
                    
                    console.log("Cliente antiguo: se omiten fotos del DNI.");
                }

            } else {
                // console.log("Cliente nuevo.");
                // Si el cliente no existe, nos aseguramos de que la sección de fotos sea visible
                const seccionFotos = document.getElementById('seccion-dni');
                const inputFrente = document.querySelector('input[name="dni_frente"]');
                const inputDorso = document.querySelector('input[name="dni_dorso"]');
                
                if (seccionFotos) {
                    seccionFotos.style.display = 'block';
                    if (inputFrente) inputFrente.required = true;
                    if (inputDorso) inputDorso.required = true;
                }
            }
        } catch (error) {
            console.error("Error al buscar cliente:", error);
        }
    }
});

// Y acá pegamos la función completa para que el navegador la conozca
function iniciarAutocompletado() {
    const inputDni = document.querySelector('input[name="dni"]');
    if (!inputDni) return;

    // Función interna para buscar (la separamos para reusarla)
    const ejecutarBusqueda = async () => {
        const dniVal = inputDni.value.trim();
        
        // Solo buscamos si tiene entre 7 y 9 caracteres (DNI estándar)
        if (dniVal.length >= 7 && dniVal.length <= 10) {
            console.log("Intentando buscar DNI:", dniVal);
            try {
                const response = await fetch(`buscar_cliente.php?dni=${dniVal}`);
                const resultado = await response.json();

                if (resultado.existe) {
                    const campos = ['nombre', 'apellido', 'gmail', 'celular', 'domicilio', 'residencia', 'nacionalidad'];
                    campos.forEach(campo => {
                        const el = document.querySelector(`input[name="${campo}"]`);
                        if (el) {
                            el.value = resultado.datos[campo];
                            el.style.backgroundColor = "#e8f0fe";
                        }
                    });

                    const seccionFotos = document.getElementById('seccion-dni');
                    if (seccionFotos) {
                        seccionFotos.style.display = 'none';
                        document.querySelector('input[name="dni_frente"]').required = false;
                        document.querySelector('input[name="dni_dorso"]').required = false;
                    }
                }
            } catch (error) {
                console.error("Error en autocompletado:", error);
            }
        }
    };

    // ESCUCHA 1: Cuando el usuario hace clic afuera
    inputDni.addEventListener('blur', ejecutarBusqueda);

    // ESCUCHA 2: Cuando el usuario presiona Enter
    inputDni.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault(); // Evita que se envíe el formulario antes de tiempo
            ejecutarBusqueda();
        }
    });

    // ESCUCHA 3: Por si el navegador autocompleta solo (cambio de valor)
    inputDni.addEventListener('change', ejecutarBusqueda);
}

function cancelarReserva(id) {
    if (confirm("¿Estás seguro de cancelar esta reserva? Esto liberará el turno en el calendario.")) {
        fetch(`cancelar_reserva.php?id=${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Recargamos para ver el cambio
                }
            });
    }
}


// Arrancar el sistema al cargar la página
document.addEventListener("DOMContentLoaded", ()=>{
    cargarDisponibilidad();
    iniciarAutocompletado()
});