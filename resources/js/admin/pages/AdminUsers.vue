<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <div style="display: flex;flex-direction: row;justify-content: space-between">
                        <div><p class="_title0">Admins</p></div>
                        <div>
                            <Button icon="ios-add" @click="addModal=true">Add admin</Button>
                        </div>
                    </div>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->


                            <!-- ITEMS -->
                            <tr v-for="(user,i) in users" :key="i" v-if="users.length">
                                <td>{{ user.id }}</td>
                                <td class="_table_name2">{{ user.fullName }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.userType }}</td>
                                <td>{{ user.created_at }}</td>
                                <td>
                                    <Button type="default" shape="circle" icon="md-trash"
                                            @click="showDeleteModal(user,i)" :loading="user.isDeleting"></Button>
                                    <Button type="primary" shape="circle" icon="md-create"
                                            @click="showEditModal(user,i)"></Button>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
                <!--ADD TAG MODAL-->
                <Modal
                    v-model="addModal"
                    title="Add admin"
                    :closable="false">
                    <div class="space">
                        <Input type="text" v-model="data.fullName" placeholder="Full Name:"/>
                    </div>
                    <div class="space">
                        <Input type="email" v-model="data.email" placeholder="Email:"/>
                    </div>
                    <div class="space">
                        <Input type="password" v-model="data.password" placeholder="Password:"/>
                    </div>
                    <div class="space">
                        <Select v-model="data.userType" placeholder="Select User Type:">
                            <Option value="Admin">Admin</Option>
                            <Option value="Editor">Editor</Option>
                        </Select>
                    </div>
                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Close</Button>
                        <Button type="info" @click="addAdmin" :disabled="isAdding" :loading="isAdding">
                            {{ isAdding ? 'Adding...' : 'Add admin' }}
                        </Button>
                    </div>
                </Modal>
                <!--ADD TAG MODAL-->
                <!--EDIT TAG MODAL-->
                <Modal
                    v-model="editModal"
                    title="Edit admin"
                    :closable="false">
                    <div class="space">
                        <Input type="text" v-model="editData.fullName" placeholder="Full Name:"/>
                    </div>
                    <div class="space">
                        <Input type="email" v-model="editData.email" placeholder="Email:"/>
                    </div>
                    <div class="space">
                        <Input type="password" v-model="editData.password" placeholder="Password:"/>
                    </div>
                    <div class="space">
                        <Select v-model="editData.userType" placeholder="Select User Type:">
                            <Option value="Admin">Admin</Option>
                            <Option value="Editor">Editor</Option>
                        </Select>
                    </div>
                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Close</Button>
                        <Button type="info" @click="editAdmin" :disabled="isAdding" :loading="isAdding">
                            {{ isAdding ? 'Editing' : 'Edit tag' }}
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
            getDeleteModalObj(obj) {
                if (obj.isDeleted) {
                    this.users.splice(obj.deletingIndex, 1);
                    // console.log(obj.deletingIndex);
                }
            }
        },
    data() {
        return {
            data: {
                fullName: '',
                email: '',
                password: '',
                userType: 'Admin',
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            deleteModal: false,
            users: [],
            editData: {
                fullName: '',
                email: '',
                password: '',
                userType: '',
            },
            index: -1,
            deleteItem: {}
        }
    },
    methods: {

        async addAdmin() {

            if (this.data.fullName.trim() == '') return this.error(true, 'Full name');
            if (this.data.email.trim() == '') return this.error(true, 'Email');
            if (this.data.password.trim() == '') return this.error(true, 'Password');
            if (this.data.userType.trim() == '') return this.error(true, 'User type');
            this.isAdding = true
            const res = await this.callApi('post', 'app/create_useradmin', this.data);
            if (res.status === 201) {
                this.users.unshift(res.data)
                this.s('Admin has been added successfully!')
                this.addModal = false
                this.isAdding = false
            } else {
                if (res.status === 422) {
                    for(let i in res.data.errors)
                    {
                        this.error(true,res.data.errors[i][0]+' new email')
                    }
                } else {
                    this.swr()
                }


            }

            this.data.tagName = ''
            this.isAdding = false
        },
        showEditModal(user, i) {
            let obj = {
                fullName:user.fullName,
                email:user.email,
                id:user.id,
                userType:user.userType
            }
            this.editData = obj
            this.editModal = true
            this.index = i
        },
        async editAdmin() {
            if (this.editData.fullName.trim() == '') return this.error(true, 'User name')
            if (this.editData.email.trim() == '') return this.error(true, 'Email')
            if (this.editData.userType.trim() == '') return this.error(true, 'User type')
            this.isAdding = true
            const res = await this.callApi('post', 'app/edit_useradmin', this.editData);
            if (res.status === 200) {
                this.users[this.index] = this.editData
                this.s('User has been edited successfully!')
                this.editModal = false
                this.isAdding = false
            } else {
                if (res.status === 422) {
                    for(let i in res.data.errors)
                    {
                        this.error(true,res.data.errors[i][0]+' new email')
                    }
                } else {
                    this.swr()
                }


            }

            this.isAdding = false
        },
        async deleteTag() {
            this.isAdding = true
            const res = await this.callApi('post', 'app/delete_tag', this.deleteItem)
            if (res.status === 200) {
                this.tags.splice(this.index, 1)
                this.s('This tag has been deleted successfully!')
                this.isAdding = false
                this.deleteModal = false
            } else this.swr()
            this.isAdding = false

        },
        showDeleteModal(user, i) {
            const deleteModalObj =
                {
                    deleteModal: true,
                    dataUrl: 'app/delete_useradmin',
                    data: user,
                    deletingIndex: i,
                    isDeleted: false
                }
            this.$store.commit('setDeleteModalObj', deleteModalObj);
            // console.log(deleteModalObj.deletingIndex);
        },

    },
    async created() {
        const res = await this.callApi('get', 'app/get_useradmin')
        if (res.status === 200) {
            this.users = res.data
        } else {
            this.swr()
        }
    }
}
</script>

