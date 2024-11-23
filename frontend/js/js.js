const URL_BASE_API = "http://127.0.0.1:8000/api/alumnos"; 

const cargarAlumnos = async (parametrosBusqueda = {}) => {
    try {
        const urlAlumnos = new URL(URL_BASE_API);
        
        Object.entries(parametrosBusqueda).forEach(([clave, valor]) => {
            if (valor) {
                urlAlumnos.searchParams.append(clave, valor);
            }
        });

        const respuesta = await fetch(urlAlumnos);
        if (!respuesta.ok) throw new Error('Error en la respuesta de la red');

        const datos = await respuesta.json();
        const cuerpoTabla = document.querySelector("#alumnos tbody");
        cuerpoTabla.innerHTML = "";  

        datos.data.forEach((alumno) => {
            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td>${alumno.cod}</td>
                <td>${alumno.nombres}</td>
                <td>${alumno.email}</td>
                <td class="nota_definitiva">${alumno.nota_definitiva || 'N/A'}</td>
                <td class="estado">${alumno.estado || 'Sin notas'}</td>
                <td>
                    <button type="button" class="eliminar-btn" data-codigo="${alumno.cod}" onclick="eliminarAlumno(this)">Eliminar</button>
                </td>
            `;
            cuerpoTabla.appendChild(fila);
        });

        const resumenElemento = document.getElementById("resumen");
        resumenElemento.innerHTML = `
            <p>Aprobados: ${datos.resumen.aprobados}</p>
            <p>Reprobados: ${datos.resumen.reprobados}</p>
            <p>Sin notas: ${datos.resumen.sin_notas}</p>
        `;
    } catch (error) {
        console.error("Error al cargar los alumnos:", error);
    }
};

const eliminarAlumno = async (button) => {
    const codigoAlumno = button.closest('tr').querySelector('td:first-child').textContent; // Obtener el código del alumno

    if (confirm("¿Estás seguro de que deseas eliminar a este alumno?")) {
        try {
            const respuesta = await fetch(`${URL_BASE_API}/${codigoAlumno}`, {
                method: "DELETE",
            });
            const datos = await respuesta.json();
            alert(datos.msg);
            cargarAlumnos(); // Recargar la lista de alumnos después de eliminar
        } catch (error) {
            console.error("Error al eliminar el alumno:", error);
        }
    }
};

const validarNota = (nota) => {
    return nota >= 0 && nota <= 5;
};

const agregarAlumno = async (button) => {
    const row = button.closest('tr');
    const codigo = row.querySelector('input[name="codigo_alumno"]').value;
    const nombre = row.querySelector('input[name="nombre_alumno"]').value;
    const email = row.querySelector('input[name="email_alumno"]').value;
    const nota1 = parseFloat(row.querySelector('input[name="nota1"]').value) || 0;
    const nota2 = parseFloat(row.querySelector('input[name="nota2"]').value) || 0;
    const nota3 = parseFloat(row.querySelector('input[name="nota3"]').value) || 0;
    const nota4 = parseFloat(row.querySelector('input[name="nota4"]').value) || 0;

    // Validar las notas
    if (![nota1, nota2, nota3, nota4].every(validarNota)) {
        alert("Las notas deben estar entre 0 y 5.");
        return;
    }

    const promedio = (nota1 + nota2 + nota3 + nota4) / 4;

    const promedioElemento = row.querySelector('.nota_definitiva');
    if (promedioElemento) {
        promedioElemento.textContent = promedio.toFixed(2);
    } else {
        console.error("Elemento para mostrar promedio no encontrado");
    }

    const estado = promedio >= 3 ? "Aprobado" : "Reprobado";

    const estadoElemento = row.querySelector('.estado');
    if (estadoElemento) {
        estadoElemento.textContent = estado;
    } else {
        console.error("Elemento para mostrar estado no encontrado");
    }

    const data = {
        cod: codigo,
        nombres: nombre,
        email: email,
        nota_definitiva: promedio,
        estado: estado
    };

    try {
        const respuesta = await fetch(URL_BASE_API, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });
        if (!respuesta.ok) throw new Error('Error al agregar el alumno');
        const respuestaDatos = await respuesta.json();
        alert('Alumno agregado exitosamente');
        cargarAlumnos(); 
    } catch (error) {
        console.error("Error al agregar el alumno:", error);
        alert('Error al agregar alumno: ' + error.message);
    }
};

document.getElementById('filtros').addEventListener('submit', (evento) => {
    evento.preventDefault();

    const parametrosBusqueda = {
        codigo: document.getElementById('codigo').value,
        nombre: document.getElementById('nombre').value,
        email: document.getElementById('email').value,
        estado: document.getElementById('estado') ? document.getElementById('estado').value : '',
        sin_notas: document.getElementById('sin_notas').checked ? 1 : 0,
    };
    
    cargarAlumnos(parametrosBusqueda);
});

cargarAlumnos();