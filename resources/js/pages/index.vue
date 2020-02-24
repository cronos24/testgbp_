<template>
    <section class="conf-section m-3 p-3">
        <div class="row mx-0">
            <div class="col-xl-4 px-0">
                <!-- bodegas -->
                <div class="border-dark p-2 br-5 my-2">
                    <div class="row mx-0 pl-2">
                        <b class="mr-2 my-auto">Bodegas</b>
                        <el-popover placement="bottom" title="Crear bodega" width="240" trigger="click" >
                            <div class="row mx-0 my-2">
                                <div class="col">
                                    <label>Nombre Bodega</label>
                                    <input type="text" class="form-control" name="" value="" />
                                </div>
                            </div>
                            <div class="row mx-0 my-2">
                                <div class="col">
                                    <label>Responsable</label>
                                    <el-select v-model="value" placeholder="Select"> <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value" /> </el-select>
                                </div>
                            </div>
                            <div class="row mx-0 mt-2 justify-content-end">
                                <button type="button" class="btn btn-sm btn-light mr-2" name="button">Cerrar</button>
                                <button type="button" class="btn btn-sm btn-primary" name="button">Guardar</button>
                            </div>
                            <i slot="reference" class="mdi mdi-plus-circle f-22 cr-pointer" />
                        </el-popover>
                    </div>
                    <div class="row mx-0 my-3">
                        <p class="mx-2 my-auto"> Bodega 1 </p>
                        <el-tooltip class="item" effect="light" content="Roberto Gómez Bolaños" placement="top">
                            <img class="rounded-circle mx-2 obj-cover" height="40" width="40" src="/img/usuarios/anni.jpg" alt="" />
                        </el-tooltip>
                        <button type="button" :class="`btn btn-sm my-auto ${respBodega?'btn-success':'btn-danger'}`" name="button" @click="cambiarEstadoUsuario" >
                            {{respBodega?'Activo':'Inactivo'}}
                        </button>
                    </div>
                </div>
                <!-- usuarios -->
                <div class="border-dark p-2 br-5 my-2">
                    <div class="row mx-0 justify-conent-center pl-2">
                        <b class="mr-2 my-auto">Usuarios</b>
                        <i class="mdi mdi-plus-circle f-22 cr-pointer" />
                    </div>
                    <div v-for="(data, key) in users" :key="`user-${key}`" class="row mx-0 my-3 px-2">
                        <el-switch v-model="data.estado" :inactive-text="data.name"> </el-switch>
                    </div>
                </div>
                <!-- Productos -->
                <div class="border-dark p-2 br-5 my-2">
                    <div class="row mx-0 justify-conent-center">
                        <b class="mr-2 my-auto">Productos</b>
                        <i class="mdi mdi-plus-circle f-22 cr-pointer" />
                    </div>
                    <div v-for="(data, key) in 2" :key="`prod-${key}`" class="row mx-0 my-3">
                        <p class="mx-2 my-auto">Tenis nike</p>
                        <button type="button" :class="`btn btn-sm ${product?'btn-success':'btn-danger'}`" name="button" @click="product=!product">{{product?'Activo':'Inactivo'}}</button>
                    </div>
                </div>
            </div>
            <!-- tablas -->
            <div class="col-xl-8">
                <div class="row mx-0 my-3">
                    <div class="col d-flex">
                        <label class="mr-2">Rojo</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="ColorTabla">
                            <label class="custom-control-label" for="ColorTabla">Azul</label>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success my-auto btn-sm d-flex" name="button" @click="agregarProducto">
                        <span class="my-auto">Agregar producto</span>
                        <i class="mdi mdi-plus-box cr-pointer f-17 ml-2 my-n1" />
                    </button>
                    <button type="button" class="btn btn-primary ml-2 my-auto btn-sm d-flex" name="button">
                        <span class="my-auto">Transferir producto</span>
                        <i class="mdi mdi-reload f-17 ml-2 my-n1" />
                    </button>
                </div>
                <div class="row mx-0 my-3">
                    <div class="col overflow-auto">
                        <p class="f-600 my-2 text-center f-18">Productos en bodega </p>
                        <table id="listado_por_legal" class="table table-sm table-hover table-striped ">
                            <thead>
                                <tr>
                                    <th class="align-middle">Item</th>
                                    <th class="align-middle">Bodega</th>
                                    <th class="align-middle">Producto</th>
                                    <th class="align-middle">Cantidad</th>
                                    <th class="align-middle text-center">Fecha</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="row mx-0 my-3">
                    <div class="col overflow-auto">
                        <p class="f-600 my-2 text-center f-18">Historial de movimientos</p>
                    </div>
                </div>
            </div>
        </div>
        <modal-agregar-producto ref="modalAgregarProducto" />
        <modal-transferir-producto ref="modalTransferirProducto" />
        <modal ref="cambiarEstado" titulo="Cambiar estado usuario">
            <div class="row mx-0 my-3 justify-content-center">
                ¿Está seguro de Activar/Desactivar este usuario?
            </div>
            <div class="row mx-0 mt-2 justify-content-end bg-whitesmoke py-2">
                <button type="button" class="btn btn-sm btn-secondary mr-2" name="button">Cerrar</button>
                <button type="button" class="btn btn-sm btn-primary" name="button">Guardar</button>
            </div>
        </modal>
        <!-- https://datatables.net/manual/ -->
        <!-- https://element.eleme.io/#/es -->
    </section>
</template>

<script>
export default {
    components: {
        modalAgregarProducto: () => import('./modales/modalAgregarProducto'),
        modalTransferirProducto: () => import('./modales/modalTransferirProducto'),
        modal: () => import('~/components/modal')
    },
    data(){return {
        respBodega: false,
        product: false,
        colorTabla: 0,
        users: [
            { name: 'Maria Antonieta', activo: false },
            { name: 'Arnold Schwarzenegger', activo: false },
        ],
        options: [{
            value: 'Option1',
            label: 'Option1'
        }, {
            value: 'Option2',
            label: 'Option2'
        }, {
            value: 'Option3',
            label: 'Option3'
        }, {
            value: 'Option4',
            label: 'Option4'
        }, {
            value: 'Option5',
            label: 'Option5'
        }],
        value: '',
        tabla_empresa:[
            { bodega: "Bodega 1",producto: "MFDA12678934501", cantidad: 5, fecha:'12/15/7894' },
            { bodega: "Bodega 2",producto: "MFDA12678934501", cantidad: 15, fecha:'12/15/7894' },
            { bodega: "Bodega 3",producto: "MFDA12678934501", cantidad: 25, fecha:'12/15/7894' },
            { bodega: "Bodega 4",producto: "MFDA12678934501", cantidad: 35, fecha:'12/15/7894' },
            { bodega: "Bodega 5",producto: "MFDA12678934501", cantidad: 45, fecha:'12/15/7894' },
        ],
    } },
    mounted(){
        this.initDatatables();
    },
    methods: {
        cambiarEstadoUsuario(){ this.$refs.cambiarEstado.toggle() },
        agregarProducto(){ this.$refs.modalAgregarProducto.toggle() },
        trasnferirProducto(){ this.$refs.modalTransferirProducto.toggle() },
        initDatatables(){
            let that = this;
            let listado_por_legal = $("#listado_por_legal").DataTable({
                searching:false,
                // ordering:false,
                paging:false,
                // info:false,
                data: that.tabla_empresa,
                createdRow: function (row, data, index) {
                    $(row).addClass("cursor-pointer slc-row");
                    $(row).attr('data-id', data.id);
                },
                columns:[
                    { title:'Item', data:null, className:'align-middle text-center', render(data,type,row,meta){ return meta.row + 1; } },
                    { data:'bodega', className:'align-middle' },
                    { data:'producto', className:'align-middle' },
                    { data:'cantidad', className:' text-center align-middle' },
                    { data:'fecha', className:'text-center align-middle' },
                ],

            })
        } //fin datatable
    }
}
</script>

<style lang="css" scoped>
.border-dark{
    border: 1px solid #8a8a8a
}
</style>
