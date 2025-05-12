{{-- resources/views/ot/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Crear Orden de Trabajo
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow" x-data="otForm()">
                <form method="POST" action="{{ route('ot.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        {{-- Cliente --}}
                        <div class="sm:col-span-2">
                            <x-input-label for="cliente_input" value="Cliente" />
                            <input
                                id="cliente_input"
                                type="text"
                                list="clientes_list"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2"
                                x-model="clienteLabel"
                                @change="setCliente"
                                placeholder="Empieza a escribir para buscar…"
                                required />
                            <datalist id="clientes_list">
                                @foreach($clientes as $id => $nombre)
                                <option data-id="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </datalist>
                            <input type="hidden" name="id_cliente" :value="clienteId" />
                            <x-input-error :messages="$errors->get('id_cliente')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        {{-- Responsable --}}
                        <div class="sm:col-span-2">
                            <x-input-label for="responsable_input" value="Responsable" />
                            <input
                                id="responsable_input"
                                type="text"
                                list="responsables_list"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2"
                                x-model="responsableLabel"
                                @change="setResponsable"
                                placeholder="Empieza a escribir para buscar…"
                                required />
                            <datalist id="responsables_list">
                                @foreach($responsables as $id => $nombre)
                                <option data-id="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </datalist>
                            <input type="hidden" name="id_responsable" :value="responsableId" />
                            <x-input-error :messages="$errors->get('id_responsable')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        {{-- Estado --}}
                        <div>
                            <x-input-label for="id_estado" value="Estado" />
                            <select
                                id="id_estado"
                                name="id_estado"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2"
                                x-model="form.id_estado"
                                required>
                                <option value="">Seleccione un estado</option>
                                @foreach($estados as $id => $nombre)
                                <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_estado')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        {{-- Fecha entrega --}}
                        <div>
                            <x-input-label for="fecha_entrega" value="Fecha Estimada de Entrega" />
                            <x-text-input
                                id="fecha_entrega"
                                name="fecha_entrega"
                                type="date"
                                class="w-full"
                                x-model="form.fecha_entrega" />
                            <x-input-error :messages="$errors->get('fecha_entrega')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        {{-- Descripción --}}
                        <div class="sm:col-span-2">
                            <x-input-label for="descripcion" value="Descripción" />
                            <textarea
                                id="descripcion"
                                name="descripcion"
                                x-model="form.descripcion"
                                rows="4"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2"
                                placeholder="Escribe una descripción detallada..."></textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>
                    </div>

                    {{-- Servicios dinámicos full width --}}
                    <div class="mt-6">
                        <x-input-label value="Tipos de Trabajo" />
                        <template x-for="(s,i) in form.servicios" :key="i">
                            <div class="flex items-center space-x-2 mt-2">
                                <input
                                    type="text"
                                    list="servicios_list"
                                    class="flex-1 rounded p-2 border-gray-300 dark:bg-gray-700 dark:text-white"
                                    x-model="s.label"
                                    @change="setServicio(i)"
                                    placeholder="Escribe para buscar…"
                                    required />
                                <button
                                    type="button"
                                    @click="removeServicio(i)"
                                    class="px-2 py-1 bg-red-500 text-white rounded inline-flex items-center justify-center">✕</button>
                                <input type="hidden" name="servicios[]" :value="s.id" />
                            </div>
                        </template>
                        <datalist id="servicios_list">
                            @foreach($servicios as $id => $nombre)
                            <option data-id="{{ $id }}">{{ $nombre }}</option>
                            @endforeach
                        </datalist>
                        <button
                            type="button"
                            @click="addServicio()"
                            class="mt-2 px-4 py-2 bg-green-500 text-white rounded">+ Agregar Tipo de Trabajo</button>
                        <x-input-error :messages="$errors->get('servicios')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    {{-- Productos dinámicos full width --}}
                    <div class="mt-6">
                        <x-input-label value="Productos Asociados" />
                        <template x-for="(p,i) in form.productos" :key="i">
                            <div class="flex items-center space-x-2 mt-2">
                                <input
                                    type="text"
                                    list="productos_list"
                                    class="flex-1 rounded p-2 border-gray-300 dark:bg-gray-700 dark:text-white"
                                    x-model="p.label"
                                    @change="setProducto(i)"
                                    placeholder="Escribe para buscar…"
                                    required />
                                <input
                                    type="number"
                                    class="w-20 rounded p-2 border-gray-300 dark:bg-gray-700 dark:text-white"
                                    x-model.number="p.cantidad"
                                    min="1"
                                    placeholder="Cant."
                                    required />
                                <button
                                    type="button"
                                    @click="removeProducto(i)"
                                    class="px-2 py-1 bg-red-500 text-white rounded inline-flex items-center justify-center">✕</button>
                                <input type="hidden" :name="`productos[${i}][id]`" :value="p.id" />
                                <input type="hidden" :name="`productos[${i}][cantidad]`" :value="p.cantidad" />
                            </div>
                        </template>
                        <datalist id="productos_list">
                            @foreach($productos as $prod)
                            <option data-id="{{ $prod->id_producto }}">{{ $prod->marca }} {{ $prod->modelo }} — Stock: {{ optional($prod->inventario->first())->stock_total ?? 0 }}</option>
                            @endforeach
                        </datalist>
                        <button
                            type="button"
                            @click="addProducto()"
                            class="mt-2 px-4 py-2 bg-green-500 text-white rounded">+ Agregar Producto</button>
                        <x-input-error :messages="$errors->get('productos')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    {{-- Archivos Adjuntos --}}
                    <div class="mt-6">
                        <x-input-label for="archivos" value="Archivos Adjuntos" />
                        <input
                            id="archivos"
                            name="archivos[]"
                            type="file"
                            multiple
                            class="w-full text-sm text-gray-900 dark:text-gray-200" />
                        <x-input-error :messages="$errors->get('archivos')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    {{-- Botones --}}
                    <div class="mt-6 flex justify-end space-x-2">
                        <x-secondary-button @click.prevent="resetForm()">Limpiar</x-secondary-button>
                        <a href="{{ route('ot.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Cancelar</a>
                        <x-primary-button type="submit">Crear Orden</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('otForm', () => ({
                clienteId: '',
                clienteLabel: '',
                responsableId: '',
                responsableLabel: '',
                form: {
                    id_estado: '{{ old("id_estado") }}',
                    fecha_entrega: '{{ old("fecha_entrega") }}',
                    servicios: [{
                        id: '',
                        label: ''
                    }],
                    productos: [{
                        id: '',
                        label: '',
                        cantidad: 1
                    }],
                    descripcion: '{{ old("descripcion") }}',
                },

                setCliente() {
                    const opt = Array.from(document.querySelectorAll('#clientes_list option'))
                        .find(o => o.textContent.trim() === this.clienteLabel.trim());
                    this.clienteId = opt?.dataset.id || '';
                },
                setResponsable() {
                    const opt = Array.from(document.querySelectorAll('#responsables_list option'))
                        .find(o => o.textContent.trim() === this.responsableLabel.trim());
                    this.responsableId = opt?.dataset.id || '';
                },

                addServicio() {
                    this.form.servicios.push({
                        id: '',
                        label: ''
                    })
                },
                removeServicio(i) {
                    this.form.servicios.splice(i, 1)
                },
                setServicio(i) {
                    const opt = Array.from(document.querySelectorAll('#servicios_list option'))
                        .find(o => o.textContent.trim() === this.form.servicios[i].label.trim());
                    this.form.servicios[i].id = opt?.dataset.id || '';
                },

                addProducto() {
                    this.form.productos.push({
                        id: '',
                        label: '',
                        cantidad: 1
                    })
                },
                removeProducto(i) {
                    this.form.productos.splice(i, 1)
                },
                setProducto(i) {
                    const txt = this.form.productos[i].label.split('—')[0].trim();
                    const opt = Array.from(document.querySelectorAll('#productos_list option'))
                        .find(o => o.textContent.trim().startsWith(txt));
                    this.form.productos[i].id = opt?.dataset.id || '';
                },

                resetForm() {
                    this.clienteId = '';
                    this.clienteLabel = '';
                    this.responsableId = '';
                    this.responsableLabel = '';
                    this.form = {
                        id_estado: '',
                        fecha_entrega: '',
                        servicios: [{
                            id: '',
                            label: ''
                        }],
                        productos: [{
                            id: '',
                            label: '',
                            cantidad: 1
                        }],
                        descripcion: '',
                    };
                },
            }));
        });
    </script>
</x-app-layout>