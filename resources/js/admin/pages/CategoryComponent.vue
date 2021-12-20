<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <div style="display: flex;flex-direction: row;justify-content: space-between">
                        <div> <p class="_title0">Categories</p></div>
                        <div>
                            <Button icon="ios-add" @click="addModal=true" v-if="isWritePermitted">
                                Add category
                            </Button>
                        </div>
                    </div>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>Icon image</th>
                                <th>Category name</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->


                            <!-- ITEMS -->
                            <tr v-for="(category,i) in categories" :key="i" v-if="categories.length">
                                <td>{{category.id}}</td>
                                <td class="table_image">
                                    <img :src="category.IconImage"/>

                                </td>
                                <td class="_table_name2">{{category.categoryName}}</td>
                                <td>{{ category.created_at }}</td>
                                <td>
                                    <Button type="default" shape="circle" icon="md-trash" @click="showDeleteModal(category,i)" :loading="category.isDeleting" v-if="isDeletePermitted"></Button>
                                    <Button type="primary" shape="circle" icon="md-create" @click="showEditModal(category,i)" v-if="isUpdatePermitted"></Button>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
                <!--ADD CATEGORY MODAL-->
                <Modal
                    v-model="addModal"
                    title="Add category"
                    :closable="false">
                    <Input v-model="data.categoryName" placeholder="Enter a name..."  />
                    <div class="space"></div>
                    <Upload
                        ref="upload"
                        type="drag"
                        :headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
                        :on-success="handleSuccess"
                        :on-error="handleError"
                        :format="['jpg','jpeg','png']"
                        :max-size="2048"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        action="app/upload">
                        <div style="padding: 20px 0">
                            <Icon type="md-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>

                    <div class="demo-upload-list" v-if="data.IconImage">
                        <img :src="`${data.IconImage}`">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteImage(true)"></Icon>
                        </div>
                    </div>

                    <div slot="footer">
                        <Button type="default" @click="closeAddModal">Close</Button>
                        <Button type="primary" @click="addCategory" :disabled="isAdding" :loading="isAdding" v-if="isWritePermitted">
                            {{isAdding ? 'Adding...' : 'Add category'}}
                        </Button>
                    </div>
                </Modal>
                <!--ADD CATEGORY MODAL-->
                <!--EDIT CATEGORY MODAL-->
                <Modal
                    v-model="editModal"
                    title="Edit category"
                    :closable="false">
                    <Input v-model="editData.categoryName" placeholder="Enter a name..."  />
                    <div class="space"></div>
                    <Upload
                        v-show="isIconImageNew"
                        ref="editDataUpload"
                        :max-size="2048"
                        :on-exceeded-size="handleMaxSize"
                        :on-success="handleSuccess"
                        :headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
                        :on-error="handleError"
                        type="drag"
                        action="app/upload"
                        :format="['jpg','jpeg','png']"
                        :on-format-error="handleFormatError">
                        <div style="padding: 20px 0">
                            <Icon type="md-cloud-upload" size="52" style="color: #5cadff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>
                    <div class="demo-upload-list" v-show="editData.IconImage">
                        <img :src="`${editData.IconImage}`">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
                        </div>
                    </div>

                    <div slot="footer">
                        <Button type="default" @click="closeEditModal">Close</Button>
                        <Button type="info" @click="editCategory" :disabled="isAdding" :loading="isAdding" v-if="isUpdatePermitted">
                            {{isAdding ? 'Editing' : 'Edit category'}}
                        </Button>
                    </div>
                </Modal>
                <!--EDIT CATEGORY MODAL-->
                <!--DELETE CATEGORY MODAL-->
                <delete-modal></delete-modal>
                <!--DELETE CATEGORY MODAL-->
            </div>
        </div>
    </div>
</template>

<script>
import DeleteModal from '../components/DeleteModal.vue'
import {mapGetters} from "vuex";
export default {
    components:
        {
            DeleteModal
        },
    computed:
        {
            ...mapGetters(['getDeleteModalObj'])
        },
    watch:
        {
            getDeleteModalObj(obj)
            {
                if (obj.isDeleted)
                {
                    this.categories.splice(obj.deletingIndex,1);
                    console.log("in watch"+obj.deletingIndex);
                }
            }
        },
    data(){
        return{
            data:{
                categoryName:'',
                IconImage:''
            },
            addModal:false,
            editModal:false,
            isDeleting:false,
            isAdding:false,
            deleteModal:false,
           categories:[],
            editData:{
                categoryName:'',
                IconImage:''
            },
            index:-1,
            deleteItem:{},
            token :'',
            isIconImageNew:false,
            isEditingItem:false
        }
    },
    methods:{

        async addCategory() {
            console.log(this.data.IconImage)
            console.log('add category enter')
            if (this.data.categoryName.trim() == '') return this.error(true,'Category name')
            if (this.data.IconImage.trim() == '') return this.error(true,'Image icon')
            this.isAdding = true
            const res = await this.callApi('post', 'app/create_category', this.data);
            if (res.status===201){
                this.categories.unshift(res.data)
                this.s('Category has been added successfully!')
                this.addModal=false
            }
            else {
                if (res.status === 422){
                    if(res.data.errors.categoryName){
                        this.i(res.data.errors.categoryName[0])
                    }
                    if(res.data.errors.IconImage){
                        this.i(res.data.errors.IconImage[0])
                    }
                }
                else  this.swr()


            }

            this.data.categoryName = '';
            this.data.IconImage = null;
            this.isAdding=false;
            console.log(this.data.IconImage)
            console.log('add category exit')
        },
        showEditModal(category,i)
        {
            // let obj = {
            //     id : category.id,
            //     categoryName : category.categoryName,
            //     IconImage : category.iconImage
            // }
            this.editData =category;
            this.editModal=true;
            this.index=i;
            this.isEditingItem =true;
        },
        async editCategory() {
            if (this.editData.categoryName.trim() == '') return this.error(true,'Category name')
            if (this.editData.IconImage.trim() == '') return this.error(true,'Icon image')
            this.isAdding = true
            const res = await this.callApi('post', 'app/edit_category', this.editData);
            if (res.status===200){
                this.categories[this.index].categoryName = this.editData.categoryName
                this.s('Category has been edited successfully!')
                this.editModal=false
                this.isAdding=false
            }
            else {
                if (res.status === 422){
                    if(res.data.errors.categoryName){
                        this.i(res.data.errors.categoryName[0])
                    }
                    if(res.data.errors.IconImage){
                        this.i(res.data.errors.IconImage[0])
                    }
                }else { this.swr()
                }


            }

            this.isAdding=false
            },

        showDeleteModal(category,i)
        {
            const deleteModalObj =
            {
                deleteModal :true,
                dataUrl : 'app/delete_category',
                data : category,
                deletingIndex:i,
                isDeleted : false
            }
            this.$store.commit('setDeleteModalObj',deleteModalObj);
            console.log("show dlete modal"+deleteModalObj.deletingIndex)
        },
        handleSuccess (res, file) {
            console.log('handle enter')
            res = `/uploads/${res}`;
            console.log(res)
            if (this.isEditingItem) {
                this.editData.IconImage = res;
                this.isIconImageNew=false
            }
            else this.data.IconImage = res;
            console.log(this.data.IconImage)


        },
        handleError(res, file) {
            this.$Notice.warning({
                title: "The file format is incorrect",
                desc: `${
                    file.errors.file.length
                        ? file.errors.file[0]
                        : "Something went wrong!"
                }`
            });
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 2M.'
            });
        },
        async deleteImage(isAdd=true)
        {
            let image;
            if (!isAdd) {
                //edit
                this.isIconImageNew=true
                image = this.editData.IconImage
                this.editData.IconImage = ''
                this.$refs.editDataUpload.clearFiles()
            }
            else {
                //add
                 image = this.data.IconImage
                this.data.IconImage = ''
                this.$refs.upload.clearFiles()
            }
            const res = await this.callApi('post' , 'app/delete_image' , {imageName : image})
            if (res.status != 200) {
                if (!isAdd) this.editData.IconImage = image;
                else this.data.IconImage =image;
                this.swr();
            }

        },
        closeAddModal()
        {
            this.addModal=false;
            this.data.IconImage=''
            this.editData.IconImage=''
        },
        closeEditModal()
        {
            this.editModal =false
            this.isEditingItem = false;
            this.data.IconImage='';
            this.editData.IconImage='';
        }
    },
    async created(){
        this.token =  window.Laravel.csrfToken
        const res = await this.callApi('get','app/get_categories')
        if(res.status === 200){
            this.categories = res.data
        }else {
            this.swr()
        }
    }
}
</script>

<style scoped>
.demo-upload-list{
    display: block;
    width: 60px;
    height: 60px;
    text-align: center;
    line-height: 60px;
    border: 1px solid transparent;
    border-radius: 4px;
    overflow: hidden;
    background: #fff;
    position: relative;
    box-shadow: 0 1px 1px rgba(0,0,0,.2);
    margin-right: 4px;
}
.demo-upload-list img{
    width: 100%;
    height: 100%;
}
.demo-upload-list-cover{
    display: none;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,.6);
}
.demo-upload-list:hover .demo-upload-list-cover{
    display: block;
}
.demo-upload-list-cover i{
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    margin: 0 2px;
}
</style>
