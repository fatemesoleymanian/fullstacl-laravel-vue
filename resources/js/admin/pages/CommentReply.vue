<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <div style="display: flex;flex-direction: row;justify-content: space-between">
                        <div> <p class="_title0">Replies of this comment</p></div>
                    </div>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>Name</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Created at</th>
                                <th>Action</th>
                                <th> Allow to Publish
                                </th>
                            </tr>
                            <!-- TABLE TITLE -->


                            <!-- ITEMS -->
                            <tr v-for="(rep,i) in replies" :key="i" v-if="replies.length">
                                <td>{{rep.user.fullName}}</td>
                                <td class="_table_name2">{{rep.content}}</td>
                                <td class="_table_name2">{{rep.isPublished ? 'published' : 'waiting'}}</td>
                                <td>{{ rep.updated_at }}</td>
                                <td>
                                    <Button shape="circle" icon="md-trash" type="primary" @click="showDeleteModal(rep,i)">
                                    </Button>
                                </td>
                                <td>
                                    <Checkbox v-model="rep.isPublished ? true : false"
                                              @on-change="publish(rep.id,rep.isPublished)"></Checkbox>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
                <!--DELETE TAG MODAL-->
                <delete-modal></delete-modal>
                <!--DELETE TAG MODAL-->
            </div>
        </div>
    </div>
</template>

<script>
import DeleteModal from "../components/DeleteModal";
import {mapGetters} from "vuex";

export default {
    name: "CommentReply",
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
                    this.replies.splice(obj.deletingIndex, 1);
                    // console.log(obj.deletingIndex);
                }
            }
        },
    data(){
        return{
            replies:[],
            isAdding:false,
            index:-1,
            deleteItem:{}
        }
    },
    methods:{
        async publish(reply,pub){
            const obj={
                pub:!pub
            }
            const res = await this.callApi('post',`/app/change_publish_rep/${reply}`,obj);
            if (res.status === 200)
            {
                this.s('Reply status Has been changed successfully!')
            }
            else this.swr();

        },
        async deleteComment() {
            this.isAdding = true
            const res = await this.callApi('post', '/app/delete_reply', this.deleteItem)
            if (res.status === 200) {
                this.replies.splice(this.index, 1)
                this.s('This reply has been deleted successfully!')
                this.isAdding = false
                this.deleteModal = false
            } else this.swr()
            this.isAdding = false

        },
        showDeleteModal(user, i) {
            const deleteModalObj =
                {
                    deleteModal: true,
                    dataUrl: '/app/delete_reply',
                    data: user,
                    deletingIndex: i,
                    isDeleted: false,
                    msg : 'Are you sure you want to delete this reply?',
                    successMsg: 'reply has been deleted successfully!'
                }
            this.$store.commit('setDeleteModalObj', deleteModalObj);
            // console.log(deleteModalObj.deletingIndex);

        },
    },
    async created()
    {
        const id = parseInt(this.$route.params.id)
        const res = await this.callApi('get', `/app/get_replies/${id}`)
        if (res.status === 200) {
            this.replies = res.data
        } else {
            this.swr()
        }
    }
}
</script>

<style scoped>

</style>
