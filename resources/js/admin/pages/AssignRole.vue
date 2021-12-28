
    <template>
        <div>
            <div class="content">
                <div class="container-fluid">
                    <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                    <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                        <div style="display: flex;flex-direction: row;justify-content: space-between">
                            <div> <p class="_title0">Role Assignment</p></div>
                            <div class="space">
                                <Select v-model="data.id" placeholder="Select User Type:" @on-change="roleChange">
                                    <Option :value="r.id" v-for="(r,i) in roles"  :key="i" v-if="roles.length">
                                        {{r.roleName}}
                                    </Option>
                                </Select>
                            </div>
                        </div>

                        <div class="_overflow _table_div">
                            <table class="_table">
                                <!-- TABLE TITLE -->
                                <tr>
                                    <th>Resource name</th>
                                    <th>Read</th>
                                    <th>Write</th>
                                    <th>Update</th>
                                    <th>Delete</th>
                                </tr>
                                <!-- TABLE TITLE -->


                                <!-- ITEMS -->
                                <tr v-for="(resource,i) in resources" :key="i" v-if="resources.length">
                                   <td>{{resource.resourceName}}</td>
                                   <td> <Checkbox v-model="resource.read "></Checkbox></td>
                                   <td> <Checkbox v-model="resource.write "></Checkbox></td>
                                   <td> <Checkbox v-model="resource.update "></Checkbox></td>
                                   <td> <Checkbox v-model="resource.delete "></Checkbox></td>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <div class="space width">
                    <Button type="primary"style="width: 300px;" long class="center-butt" @click="assignRoles" :disabled="isSubmitting" :loading="isSubmitting" v-if="isWritePermitted">
                        {{ isSubmitting ? 'Submitting...' : 'Submit changes' }}
                    </Button>
                    </div>
                    <!--ADD Role MODAL-->

                    <!--ADD Role MODAL-->
                    <!--EDIT Role MODAL-->

                    <!--EDIT Role MODAL-->
                    <!--DELETE ROLE MODAL-->

                    <!--DELETE ROLE MODAL-->
                </div>
            </div>
        </div>
    </template>

    <script>
        import {mapGetters} from "vuex";

        export default {
            name: "AssignRole",
//roles is whole fetching from role table (includes all fields) but resources is just permission field of ONE row
            computed:
                {
                    ...mapGetters(['getUser'])
                },
            watch:
                {
                    getUser(user)
                    {
                        this.bb=user
                    },
                },
            data(){

                return{
                    data:{id:null,
                    bb:''},
                    roles:[],
                    resources:[
                        {resourceName:'Home', read:false , write:false , update:false , delete:false , name:'home' , icon:'ios-speedometer'},
                        {resourceName:'Dashboard', read:false , write:false , update:false , delete:false , name:'dashboard' , icon:'ios-speedometer'},
                        {resourceName:'Tags', read:false , write:false , update:false , delete:false , name:'tags' , icon:'md-pricetags'},
                        {resourceName:'Category', read:false , write:false , update:false , delete:false , name:'category' , icon:'md-filing'},
                        {resourceName:'Create Blog', read:false , write:false , update:false , delete:false , name:'createblog' , icon:'ios-create'},
                        {resourceName:'Blogs', read:false , write:false , update:false , delete:false , name:'blogs' , icon:'ios-paper'},
                        {resourceName:'Admin Users', read:false , write:false , update:false , delete:false , name:'admin' , icon:'ios-contact'},
                        {resourceName:'Role', read:false , write:false , update:false , delete:false , name:'role' , icon:'md-hand'},
                        {resourceName:'Assign Role', read:false , write:false , update:false , delete:false , name:'assignRole' , icon:'ios-man'},

                    ],
                    defaultResourcesPermission:[
                        {resourceName:'Home', read:false , write:false , update:false , delete:false , name:'home' , icon:'ios-speedometer'},
                        {resourceName:'Dashboard', read:false , write:false , update:false , delete:false , name:'dashboard' , icon:'ios-speedometer'},
                        {resourceName:'Tags', read:false , write:false , update:false , delete:false , name:'tags' , icon:'md-pricetags'},
                        {resourceName:'Category', read:false , write:false , update:false , delete:false , name:'category' , icon:'md-filing'},
                        {resourceName:'Create Blog', read:false , write:false , update:false , delete:false , name:'createblog' , icon:'ios-create'},
                        {resourceName:'Blogs', read:false , write:false , update:false , delete:false , name:'blogs' , icon:'ios-paper'},
                        {resourceName:'Admin Users', read:false , write:false , update:false , delete:false , name:'admin' , icon:'ios-contact'},
                        {resourceName:'Role', read:false , write:false , update:false , delete:false , name:'role' , icon:'md-hand'},
                        {resourceName:'Assign Role', read:false , write:false , update:false , delete:false , name:'assignRole' , icon:'ios-man'},
                    ],
                    isSubmitting:false

                }
            },
            methods:{
              async assignRoles()
                {
                    this.isSubmitting = true;
                    if (!this.data.id) return this.error(true,'User Type')
                    let submitted_permissions = JSON.stringify(this.resources);
                    const res = await this.callApi('post','app/assign_role',{'permission':submitted_permissions , id:this.data.id})
                    if (res.status === 200)
                    {
                      this.roles[this.data.id-1].permission = submitted_permissions;
                        this.s('Roles have been assigned successfully!');
                    }
                    else this.swr();

                    this.isSubmitting = false;
                },
                roleChange()
                {
                    let per = this.roles[this.data.id-1].permission;
                   if(!per) this.resources=this.defaultResourcesPermission
                    else this.resources=JSON.parse(per);
                },
            },
            async created() {
                console.log(this.bb)
                const res = await this.callApi('get', 'app/get_roles')
                if (res.status === 200) {
                    this.roles = res.data

                    if (res.data.length) {
                        // console.log(res.data[id].id)
                        this.data.id = res.data[0].id
                        if (res.data[0].permission) {
                            this.resources = JSON.parse(res.data[0].permission);
                        }
                    }

                }else {
                    this.swr()
                }
            }
        }
    </script>

<style scoped>
.width
{
    display: flex;
    justify-content: center;

}
</style>
