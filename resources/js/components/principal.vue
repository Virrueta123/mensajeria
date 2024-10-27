<template>

    <div>
        <div @drop.prevent="handleDrop" @dragover.prevent="handleDragOver" class="dropzone">
            Arrastra y suelta un archivo Excel aquí
        </div>

        <hr class="mt-4">

        <ul class="grid w-full gap-6 md:grid-cols-2">
            <li>
                <input type="radio" id="hosting-small" name="hosting" value="hosting-small" class="hidden peer"
                    required />
                <label for="hosting-small"
                    class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="block">
                        <div class="w-full text-lg font-semibold">Estudiante</div> 
                    </div>
                    <svg class="w-5 h-5 ms-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 5h12m0 0L9 1m4 4L9 9" />
                    </svg>
                </label>
            </li>
            <li>
                <input type="radio" id="hosting-big" name="hosting" value="hosting-big" class="hidden peer">
                <label for="hosting-big"
                    class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="block">
                        <div class="w-full text-lg font-semibold">Profesor</div> 
                    </div>
                    <svg class="w-5 h-5 ms-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 5h12m0 0L9 1m4 4L9 9" />
                    </svg>
                </label>
            </li>
        </ul>

        <button v-on:click="enviar_mensajes()"
            class="inline-block mt-2 w-auto text-center min-w-[200px] px-6 py-4 text-white transition-all rounded-md shadow-xl sm:w-auto bg-gradient-to-r from-blue-600 to-blue-500 hover:bg-gradient-to-b dark:shadow-blue-900 shadow-blue-200 hover:shadow-2xl hover:shadow-blue-400 hover:-tranneutral-y-px ">
            Enviar mensajes
        </button>

        <ul role="list" class="divide-y divide-gray-100">

            <li v-for="(row, index) in data" :key="index" class="flex justify-between gap-x-6 py-5">
                <div class="flex min-w-0 gap-x-4">
                    <img class="h-12 w-12 flex-none rounded-full bg-gray-50"
                        src="https://cdn-icons-png.freepik.com/256/3983/3983877.png?semt=ais_hybrid" alt="">
                    <div class="min-w-0 flex-auto">
                        <p class="text-sm font-semibold leading-6 text-gray-900">{{ row[1] }}</p>
                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ row[0] }}</p>
                    </div>
                </div>
                <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                    <p class="text-sm leading-6 text-gray-900">{{ row[2] }} / {{ row[3] }}</p>
                    <p v-if="row[5] !== undefined" class="mt-1 text-xs leading-5 text-gray-500"> {{ row[5] }} </p>
                    <div v-if="row[4] !== undefined && row[5] == undefined"
                        class="border-t-transparent border-solid animate-spin  rounded-full border-blue-400 border-8 h-10 w-10">
                    </div>
                </div>
            </li>

        </ul>
    </div>

</template>
<script>
import Axios from 'axios';
import * as XLSX from 'xlsx';


export default {
    data() {
        return {
            data: [],
            headers: {
                "Content-Type": "application/json",
            }
        }
    },
    methods: {
        async delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },
        async enviar_mensajes() {

            const nuevoArray = this.data.map(subArray => {
                subArray.push(0);
                return subArray;
            });

            console.log(0);

            if (this.data.length === 0) {
                alert('No hay alumnos para notificar');
                return;
            }

            for (let index = 0; index < this.data.length; index++) {
                const alumno = this.data[index];

                await this.enviar_mensaje(alumno).then((result) => {
                    if (result.success) {
                        this.data[index].push('este alumno ha sido notificado con éxito');
                    } else {
                        this.data[index].push('este alumno ya ha sido notificado anteriormente en la fecha ' + result.data.created_at);
                    }
                }).catch((err) => {
                    console.log('hubo un error al enviar el mensaje');
                });

                await this.delay(5000);
            }
        },
        async enviar_mensaje(alumno) {
            const data = {
                alumno: alumno
            };

            const headers = this.headers;

            return Axios
                .post("/enviar_mensaje", data, {
                    headers,
                })
                .then((response) => {

                    if (response.data.success) {
                        return response.data;
                    } else {
                        return response.data;
                    }
                })
                .catch((error) => {
                    return {
                        error: error,
                        success: false,
                        message: "Hubo un error al enviar el mensaje",
                    };
                });
        },
        handleDragOver(event) {
            // Cambiar el estilo para indicar que el área está lista para recibir el archivo
            event.target.style.backgroundColor = '#e3e3e3';
        },
        handleDrop(event) {
            event.target.style.backgroundColor = ''; // Restaurar el estilo original

            // Obtener el archivo del evento
            const file = event.dataTransfer.files[0];

            if (file) {
                // Leer el archivo Excel
                this.readExcelFile(file);
            }
        },
        readExcelFile(file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                // Leer el contenido del archivo
                const data = new Uint8Array(event.target.result);
                const workbook = XLSX.read(data, { type: 'array' });
                const sheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[sheetName];

                // Convertir el contenido del archivo en JSON
                let json = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

                // Filtrar filas vacías
                json = json.filter(row => row.some(cell => cell !== null && cell !== undefined && cell !== ''));

                json.map((row, rowIndex) => {
                    console.log(row[1]);
                })

                json.splice(0, 1);

                this.data = json;
            };
            reader.readAsArrayBuffer(file);
        },
    },
    mounted() {
        this.enviar_mensaje();
    }
}
</script>

<style>
.dropzone {
    width: 100%;
    height: 200px;
    border: 2px dashed #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: #666;
}
</style>