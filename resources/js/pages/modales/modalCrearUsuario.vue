<template>
    <modal ref="crearUsuario" titulo="Crear usuario">

        <div id="spinner" v-if="spinner" class="text-center">
                <spinner-component></spinner-component>
        </div>

        <form id="form_create_user" v-show="!spinner" method="post" @submit="save" enctype="multipart/form-data">
            <div class="alert alert-danger" role="alert" v-if="errors.length != 0">           
                <strong>Por favor, corrija el(los) siguiente(s) error(es):</strong>
                <ul>
                    <div v-for="(error, index) in errors" v-bind:key="index">
                        <li v-for="er in error" v-bind:key="er" v-html="er"></li>
                    </div>                
                </ul>    
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>Nombre</label>
                    <input type="text" class="form-control" name="nombre" v-model="model.nombre" required>
                    <div class="invalid-feedback">
                    "Nombre" es Obligatorio!
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label for="foto_file">Foto</label>
                    <input type="file" class="form-control-file" id="foto_file" name="foto" @change="preUpload" required>
                </div>
            </div>                      
            <br />
            <div class="text-center"> 
                <button type="submit" class="btn btn-success btn-sm"><em class="fas fa-save"></em> Guardar</button>
            </div>
        </form> 
    </modal>
</template>

<script>
export default {
    components: {
        modal: () => import('~/components/modal')
    },
    data() {
        return {
            model: {
                nombre: '',
                foto: ''
            },
            errors: [],
            spinner: false
        }
    },
    methods: {
        toggle(){
            this.$refs.crearUsuario.toggle()
        },
        preUpload(e) {
            if(e.target.files[0]) {
                this.model.foto = e.target.files[0];
            } else {
                this.model.foto = ''
            }
        },
        save: function (e) {
            e.preventDefault();
            let _form = new FormData();
            _form.append('nombre', this.model.nombre);
            _form.append('foto', this.model.foto);
     
            this.$emit('save_user', _form);
        }
    }
}
</script>

<style lang="css" scoped>
</style>
