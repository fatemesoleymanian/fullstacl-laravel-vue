<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <div style="display: flex;flex-direction: row;justify-content: space-between">
                        <div> <p class="_title0">Comments of this blog</p></div>
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
                            <tr v-for="(c,i) in comments" :key="i" v-if="comments.length">
                                <td>{{c.user.fullName}}</td>
                                <td class="_table_name2">{{c.content}}</td>
                                <td class="_table_name2">{{c.isPublished ? 'published' : 'waiting'}}</td>
                                <td>{{ c.updated_at }}</td>
                                <td>
                                    <Button type="default" @click="$router.push(`/get_reply/${c.id}`)"
                                            :loading="c.isDeleting" >Replies
                                    </Button>
                                    <Button shape="circle" icon="md-trash" type="primary" @click="showDeleteModal(c,i)">
                                    </Button>
                                </td>
                                <td>
                                    <Checkbox v-model="c.isPublished ? true : false"
                                    @on-change="publish(c.id,c.isPublished)"></Checkbox>
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
    name: "BlogComments",
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
                    this.comments.splice(obj.deletingIndex, 1);
                    // console.log(obj.deletingIndex);
                }
            }
        },
    data(){
      return{
          comments:[],
          isAdding:false,
          index:-1,
          deleteItem:{}
      }
    },
    methods:{
      async publish(comment,pub){
          const obj={
              pub:!pub
          }
         const res = await this.callApi('post',`/app/change_publish/${comment}`,obj);
          if (res.status === 200)
          {
              this.s('Comment status Has been changed successfully!')
          }
          else this.swr();

      },
        async deleteComment() {
            this.isAdding = true
            const res = await this.callApi('post', '/app/delete_comment', this.deleteItem)
            if (res.status === 200) {
                this.comments.splice(this.index, 1)
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
                    dataUrl: '/app/delete_comment',
                    data: user,
                    deletingIndex: i,
                    isDeleted: false,
                    msg : 'Are you sure you want to delete this comment?',
                    successMsg: 'comment has been deleted successfully!'
                }
            this.$store.commit('setDeleteModalObj', deleteModalObj);
            // console.log(deleteModalObj.deletingIndex);

        },
    },
    async created()
    {
        const id = parseInt(this.$route.params.id)
        console.log(id)
        const res = await this.callApi('get', `/app/get_comments/${id}`)
        if (res.status === 200) {
            this.comments = res.data
        } else {
            this.swr()
        }
    }
}
</script>

<style scoped>

</style>

