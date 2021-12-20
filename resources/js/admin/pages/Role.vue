<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <div style="display: flex;flex-direction: row;justify-content: space-between">
                        <div> <p class="_title0">Roles</p></div>
                        <div><Button icon="ios-add" @click="addModal=true" v-if="isWritePermitted">Add role</Button></div>
                    </div>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>Role name</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->


                            <!-- ITEMS -->
                            <tr v-for="(role,i) in roles" :key="i" v-if="roles.length">
                                <td>{{role.id}}</td>
                                <td class="_table_name2">{{role.roleName}}</td>
                                <td>{{ role.created_at }}</td>
                                <td>
                                    <Button type="default" shape="circle" icon="md-trash" @click="showDeleteModal(role,i)" :loading="role.isDeleting" v-if="isDeletePermitted"></Button>
                                    <Button type="primary" shape="circle" icon="md-create" @click="showEditModal(role,i)" v-if="isUpdatePermitted"></Button>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
                <!--ADD Role MODAL-->
                <Modal
                    v-model="addModal"
                    title="Add role"
                    :closable="false">
                    <Input v-model="data.roleName" placeholder="Role..."  />
                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Close</Button>
                        <Button type="info" @click="addRole" :disabled="isAdding" :loading="isAdding" v-if="isWritePermitted">
                            {{isAdding ? 'Adding...' : 'Add role'}}
                        </Button>
                    </div>
                </Modal>
                <!--ADD Role MODAL-->
                <!--EDIT Role MODAL-->
                <Modal
                    v-model="editModal"
                    title="Edit role"
                    :closable="false">
                    <Input v-model="editData.roleName" placeholder="Edit role name..."  />
                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Close</Button>
                        <Button type="info" @click="editRole" :disabled="isAdding" :loading="isAdding" v-if="isUpdatePermitted">
                            {{isAdding ? 'Editing' : 'Edit role'}}
                        </Button>
                    </div>
                </Modal>
                <!--EDIT Role MODAL-->
                <!--DELETE ROLE MODAL-->
                <delete-modal></delete-modal>

                <!--DELETE ROLE MODAL-->
            </div>
        </div>
    </div>
</template>

<script>
import DeleteModal from '../components/DeleteModal.vue';
import {mapGetters} from 'vuex';
export default {
    name:'Role',
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
                    this.roles.splice(obj.deletingIndex,1);
                    console.log(obj.deletingIndex);
                }
            }
        },
    data(){
        return{
            data:{
                roleName:''
            },
            addModal:false,
            editModal:false,
            isAdding:false,
            deleteModal:false,
            roles:[],
            editData:{
                roleName:''
            },
            index:-1,
            deleteItem:{}
        }
    },
    methods:{

        async addRole() {
            if (this.data.roleName.trim() == '') return this.error(true,'Role name')
            this.isAdding = true
            const res = await this.callApi('post', 'app/create_role', this.data);
            if (res.status===201){
                this.roles.unshift(res.data)
                this.s('Role has been added successfully!')
                this.addModal=false
                this.isAdding=false
            }
            else {
                if (res.status === 422){
                    if(res.data.errors.roleName){
                        this.i(res.data.errors.roleName[0])
                    }
                }else { this.swr()
                }


            }

            this.data.roleName=''
            this.isAdding=false
        },
        showEditModal(role,i)
        {
            let obj = {
                id : role.id,
                roleName : role.roleName
            }
            this.editData =obj
            this.editModal=true
            this.index=i
        },
        async editRole() {
            if (this.editData.roleName.trim() == '') return this.error(true,'Role name')
            this.isAdding = true
            const res = await this.callApi('post', 'app/edit_role', this.editData);
            if (res.status===200){
                this.roles[this.index].roleName = this.editData.roleName
                this.s('Role has been edited successfully!')
                this.editModal=false
                this.isAdding=false
            }
            else {
                if (res.status === 422){
                    if(res.data.errors.roleName){
                        this.i(res.data.errors.roleName[0])
                    }
                }else { this.swr()
                }


            }

            this.isAdding=false
        },
        async deleteRole(){
            this.isAdding=true
            const res = await this.callApi('post' ,'app/delete_role',this.deleteItem)
            if (res.status === 200)
            {
                this.roles.splice(this.index,1)
                this.s('This role has been deleted successfully!')
                this.isAdding=false
                this.deleteModal=false
            }
            else this.swr()
            this.isAdding=false

        },
        showDeleteModal(role,i)
        {
            const deleteModalObj =
                {
                    deleteModal :true,
                    dataUrl : 'app/delete_role',
                    data : role,
                    deletingIndex:i,
                    isDeleted : false
                }
            this.$store.commit('setDeleteModalObj',deleteModalObj);
            console.log(deleteModalObj.deletingIndex);
        },

    },
    async created(){
        const res = await this.callApi('get','app/get_roles')
        if(res.status === 200){
            this.roles = res.data
        }else {
            this.swr()
        }
    }
}
</script>

