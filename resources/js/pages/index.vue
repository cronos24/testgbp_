<template>
    <section class="conf-section m-3 p-3">
        <div class="row mx-0">
            <div class="col-xl-4 px-0">
                <!-- bodegas -->
                <div class="border-dark p-2 br-5 my-2">
                    <div class="row mx-0 pl-2">
                        <b class="mr-2 my-auto">Bodegas</b>
                        <modal ref="modalCrearBodega" titulo="Crear usuario">
                            <div id="spinner" v-if="spinner" class="text-center">
                                    <spinner-component></spinner-component>
                            </div>

                            <form id="form_bodegas" v-show="!spinner" method="post" @submit="saveBodegas">
                                <div class="alert alert-danger" role="alert" v-if="errors_bodegas.length != 0">           
                                    <strong>Por favor, corrija el(los) siguiente(s) error(es):</strong>
                                    <ul>
                                        <div v-for="(error, index) in errors_bodegas" v-bind:key="index">
                                            <li v-for="er in error" v-bind:key="er" v-html="er"></li>
                                        </div>                
                                    </ul>    
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Nombre</label>
                                        <input type="text" class="form-control form-control-sm"  v-model="model_bodega.nombre" required>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Responsable</label>
                                        <select class="form-control" v-model="model_bodega.id_responsable"  required>
                                                    <option value="" disabled selected>Seleccione un Responsable</option>
                                                    <option v-for="user in users" v-bind:key="user.id" v-bind:value="user.id">
                                                        {{ user.nombre }}
                                                    </option>
                                        </select> 
                                    </div>
                                </div>
                                <br />
                                <div class="text-center"> 
                                    <button type="submit" class="btn btn-success btn-sm"><em class="fas fa-save"></em> Guardar</button>
                                </div>
                            </form>
                        </modal>
                        <button type="button" class="btn my-auto btn-sm d-flex" name="newBodega" @click="crearBodega">
                            <i class="mdi mdi-plus-circle f-22 cr-pointer" />
                         </button>

                      
                    </div>
                    <div class="row mx-0 my-3" v-for="(data, key) in sortedItems" :key="`bodega-${key}`">
                        <p class="mx-2 my-auto"> {{ data.nombre }} </p>
                        <el-tooltip class="item" effect="light" content="Roberto Gómez Bolaños" placement="top">
                            
                            <img class="rounded-circle mx-2 obj-cover" height="40" width="40" :src="(data.user.foto !=null && data.user.foto !='')? '/storage/uploads/users/foto/'+ data.user.foto:'/img/usuarios/mickey.png'">

                        </el-tooltip>
                        <button type="button" :class="`btn btn-sm my-auto ${data.estado==1 ?'btn-success':'btn-danger'}`" name="button" @click="cambiarEstadoUsuario(data, key)" >
                            {{data.estado==1 ?'Activo':'Inactivo'}}
                        </button>
                    </div>
                </div>
                <!-- usuarios -->
                <div class="border-dark p-2 br-5 my-2">
                    <div class="row mx-0 justify-conent-center pl-2">
                        <b class="mr-2 my-auto">Usuarios</b>
                        <button type="button" class="btn my-auto btn-sm d-flex" name="newUser" @click="crearUsuario">
                            <i class="mdi mdi-plus-circle f-22 cr-pointer" />
                         </button>
                    </div>
                    <div v-for="(data, key) in users" :key="`user-${key}`" class="row mx-0 my-3 px-2">
                        <span class="switch-container">{{ data.nombre }}</span>
                        <el-switch :value="data.estado" :inactive-value="0" :active-value="1" @change="cambiarEstado(data, key)"> </el-switch>
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
                            <input type="checkbox" class="custom-control-input" id="ColorTabla" v-model="headerColor">
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
                                <tr :class="(headerColor)? 'header-blue':'header-red'">
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
                ¿Está seguro de Activar/Desactivar esta Bodega?
            </div>
            <div class="row mx-0 mt-2 justify-content-end bg-whitesmoke py-2">
                <button type="button" class="btn btn-sm btn-secondary mr-2" name="button" @click="cambiarEstadoUsuario">Cerrar</button >
                <button type="button" class="btn btn-sm btn-primary" name="button" @click="cambiarEstadoBodega">Guardar</button>
            </div>
        </modal>
        <modal-crear-usuario ref="modalCrearUsuario" v-on:save_user="saveUser"/>
        <!-- https://datatables.net/manual/ -->
        <!-- https://element.eleme.io/#/es -->
    </section>
</template>

<script>
export default {
    components: {
        modalAgregarProducto: () => import('./modales/modalAgregarProducto'),
        modalTransferirProducto: () => import('./modales/modalTransferirProducto'),
        modalCrearUsuario: () => import('./modales/modalCrearUsuario'),
        modal: () => import('~/components/modal')
    },
    computed: {
        sortedItems: function() {
            return this.bodegas.sort((a, b) => new Date(a.created_at) - new Date(b.created_at))
        }
    },
    data(){return {
        respBodega: false,
        product: false,
        spinner:false,
        colorTabla: 0,
        users: [],
        bodegas: [],
        headerColor:false,
        errors_bodegas: [],
        model_bodega: {
                nombre: '',
                id_responsable: ''
        },
        model_estado_bodega: {
                data: [],
                key: null
        },
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
        this.getAllUsers();
        this.getAllBodegas();
    },
    methods: {
        cambiarEstadoUsuario(data= null, key=null){ 
            this.model_estado_bodega= {
                data: data,
                key: key
            },
            this.$refs.cambiarEstado.toggle() 
        },
        agregarProducto(){ this.$refs.modalAgregarProducto.toggle() },
        crearUsuario(){ this.$refs.modalCrearUsuario.toggle() },
        crearBodega(){ this.$refs.modalCrearBodega.toggle() },
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
        }, //fin datatable

        getAllUsers() {
            this.axios.get('http://localhost:8000/api/users')
            .then(res => {
                console.log(res.data)
                this.users = res.data
            })
            .catch(err => {
                console.error(err);
            })
        },

        getAllBodegas() {
            this.axios.get('http://localhost:8000/api/bodegas')
            .then(res => {
                console.log('bodegas',res.data)
                this.bodegas = res.data
            })
            .catch(err => {
                console.error(err);
            })
        },

        saveUser(model) {

            this.$refs.modalCrearUsuario.spinner=true; 
            this.axios.post('http://localhost:8000/api/users', model)
            .then(response => {


                this.$refs.modalCrearUsuario.spinner=false;
                if (response.data.status=="success") { 
                        
                        this.users.push(response.data.response);  
 
                        this.$refs.modalCrearUsuario.model={};
                        this.$refs.modalCrearUsuario.errors = [];

                        this.crearUsuario();

                    }else{                          
                        this.$refs.modalCrearUsuario.errors = response.data.response; 
    
                    }
            })
            .catch(err => {
                this.$refs.modalCrearUsuario.spinner=false;
                console.error(err);
            })
        },
        cambiarEstadoBodega(){


            let model=  {
              estado: ((this.model_estado_bodega.data.estado == 0)? 1:0)
            }

            this.axios.put('http://localhost:8000/api/bodegas/'+this.model_estado_bodega.data.id, model)
            .then(response => {
                 this.$refs.cambiarEstado.toggle()
                 this.$set(this.bodegas[this.model_estado_bodega.key], 'estado', ((this.model_estado_bodega.data.estado == 0)? 1:0));
            })
            .catch(err => {
                this.$refs.cambiarEstado.toggle()
                console.error(err);
            })
        },

        cambiarEstado(data, key){

            this.$set(this.users[key], 'estado', ((data.estado == 0)? 1:0));
  
            let model=  {
              estado: data.estado
            }

            this.axios.put('http://localhost:8000/api/users/'+data.id, model)
            .then(response => {

                console.log(response.data);
            })
            .catch(err => {
                console.error(err);
            })
             
            
        },

        saveBodegas: function (e) {            
            e.preventDefault();

            this.spinner=true;
                  
            this.axios.post('http://localhost:8000/api/bodegas', this.model_bodega)
            .then(response => {

                this.spinner=false;
                if (response.data.status=="success") { 

                    console.log('this.bodegas1',this.bodegas);
                        
                        this.bodegas.push(response.data.response); 

                        console.log('this.bodegas2',this.bodegas);
 
                        this.model_bodega={};
                        this.errors_bodegas = [];

                        this.crearBodega();

                    }else{                          
                        this.errors_bodegas = response.data.response; 
    
                    }
            })
            .catch(err => {
                this.spinner=false;
                this.errors_bodegas = err; 
            })
        }

    }
}
</script>

<style lang="css" scoped>
.border-dark{
    border: 1px solid #8a8a8a
}
.switch-container{
    color: #82C7EB;
    margin-right: 5px;
}
.header-red{
    background: #ff817cc5;
}
.header-blue{
    background: #71b1fcea;
}
</style>
