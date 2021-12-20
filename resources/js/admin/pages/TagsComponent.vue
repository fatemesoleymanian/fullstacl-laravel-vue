<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <div style="display: flex;flex-direction: row;justify-content: space-between">
                        <div> <p class="_title0">Tags</p></div>
                        <div><Button icon="ios-add" @click="addModal=true" v-if="isWritePermitted">Add tag</Button></div>
                    </div>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>Tag name</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->


                            <!-- ITEMS -->
                            <tr v-for="(tag,i) in tags" :key="i" v-if="tags.length">
                                <td>{{tag.id}}</td>
                                <td class="_table_name2">{{tag.tagName}}</td>
                                <td>{{ tag.created_at }}</td>
                                <td>
                                    <Button type="default" shape="circle" icon="md-trash" @click="showDeleteModal(tag,i)" :loading="tag.isDeleting" v-if="isDeletePermitted"></Button>
                                    <Button type="primary" shape="circle" icon="md-create" @click="showEditModal(tag,i)" v-if="isUpdatePermitted"></Button>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
                <!--ADD TAG MODAL-->
                <Modal
                    v-model="addModal"
                    title="Add tag"
                    :closable="false">
                    <Input v-model="data.tagName" placeholder="Enter a name..."  />
                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Close</Button>
                        <Button type="info" @click="addTag" :disabled="isAdding" :loading="isAdding" v-if="isWritePermitted">
                            {{isAdding ? 'Adding...' : 'Add tag'}}
                        </Button>
                    </div>
                </Modal>
                <!--ADD TAG MODAL-->
                <!--EDIT TAG MODAL-->
                <Modal
                    v-model="editModal"
                    title="Edit tag"
                    :closable="false">
                    <Input v-model="editData.tagName" placeholder="Edit tag name..."  />
                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Close</Button>
                        <Button type="info" @click="editTag" :disabled="isAdding" :loading="isAdding" v-if="isUpdatePermitted">
                            {{isAdding ? 'Editing' : 'Edit tag'}}
                        </Button>
                    </div>
                </Modal>
                <!--EDIT TAG MODAL-->
                <!--DELETE TAG MODAL-->
               <delete-modal></delete-modal>

                <!--DELETE TAG MODAL-->
            </div>
        </div>
    </div>
</template>

<script>
import DeleteModal from '../components/DeleteModal.vue';
import {mapGetters} from 'vuex';
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
                    this.tags.splice(obj.deletingIndex,1);
                    console.log(obj.deletingIndex);
                }
            },
        },
    data(){
        return{
            data:{
                tagName:''
            },
            addModal:false,
            editModal:false,
            isAdding:false,
            deleteModal:false,
            tags:[],
            editData:{
                tagName:''
            },
            index:-1,
            deleteItem:{}
        }
    },
    methods:{

        async addTag() {
            if (this.data.tagName.trim() == '') return this.error(true,'Tag name')
            this.isAdding = true
            const res = await this.callApi('post', 'app/create_tag', this.data);
            if (res.status===201){
                this.tags.unshift(res.data)
                this.s('Tag has been added successfully!')
                this.addModal=false
                this.isAdding=false
            }
            else {
                if (res.status === 422){
                    if(res.data.errors.tagName){
                        this.i(res.data.errors.tagName[0])
                    }
                }else { this.swr()
                }


            }

            this.data.tagName=''
            this.isAdding=false
        },
        showEditModal(tag,i)
        {
            let obj = {
                id : tag.id,
                tagName : tag.tagName
            }
            this.editData =obj
            this.editModal=true
            this.index=i
        },
        async editTag() {
            if (this.editData.tagName.trim() == '') return this.error(true,'Tag name')
            this.isAdding = true
            const res = await this.callApi('post', 'app/edit_tag', this.editData);
            if (res.status===200){
                this.tags[this.index].tagName = this.editData.tagName
                this.s('Tag has been edited successfully!')
                this.editModal=false
                this.isAdding=false
            }
            else {
                if (res.status === 422){
                    if(res.data.errors.tagName){
                        this.i(res.data.errors.tagName[0])
                    }
                }else { this.swr()
                }


            }

            this.isAdding=false
        },
        async deleteTag(){
            this.isAdding=true
            const res = await this.callApi('post' ,'app/delete_tag',this.deleteItem)
            if (res.status === 200)
            {
                this.tags.splice(this.index,1)
                this.s('This tag has been deleted successfully!')
                this.isAdding=false
                this.deleteModal=false
            }
            else this.swr()
            this.isAdding=false

        },
        showDeleteModal(tag,i)
        {
            const deleteModalObj =
                {
                    deleteModal :true,
                    dataUrl : 'app/delete_tag',
                    data : tag,
                    deletingIndex:i,
                    isDeleted : false
                }
            this.$store.commit('setDeleteModalObj',deleteModalObj);
            console.log(deleteModalObj.deletingIndex);
        },

    },
    async created(){
        //for extra control

        if(this.isReadPermitted) {
        const res = await this.callApi('get', 'app/get_tags')

        if (res.status === 200) {
            this.tags = res.data
        } else {
            this.swr()
        }
    }
    }
}
</script>

