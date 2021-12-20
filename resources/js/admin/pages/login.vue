<template>
<div>
    <div class="container">
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _p20 margin_center col-md-4">
            <Row>
                <Col offset="4" class="py-4"><h2>Login to dashboard</h2></Col>
            </Row>
            <Form>
            <div class="space">
                <Input type="email" v-model="data.email" @keyup.enter.native="login" placeholder="Email:"/>
            </div>
            <div class="space">
                <Input type="password" v-model="data.password" @keyup.enter.native="login" placeholder="Password:"/>
            </div>
            <Button type="info" long @click="login" :disabled="isLogging" :loading="isLogging">{{isLogging ? 'Logging in...' : 'Login'}}</Button>
            </Form>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "login",
    data() {
        return {
            data: {
                email: '',
                password: ''
            },
            isLogging:false,
        }
    },
            methods:{
                async login(){
                    if (this.data.email.trim() == '') return this.error(true, 'Email');
                    if (this.data.password.trim() == '') return this.error(true, 'Password');
                    if (this.data.password.length < 6) return this.w('Password must be at least 6 charachter!');
                    this.isLogging=true;
                    const res = await this.callApi('post', 'app/admin_login',this.data)
                    if(res.status === 200)
                    {
                        this.s(res.data.msg)
                        window.location = '/home';
                    }
                    else if(res.status === 422)
                    {
                        for(let i in res.data.errors)
                        {
                            this.error(true,res.data.errors[i][0]+'')
                        }
                    }
                    else
                    {
                        if(res.status === 401) this.i(res.data.msg)
                        else this.swr();
                    }
                    this.isLogging=false;
                }
            }


}
</script>

<style scoped>
.margin_center
{
    margin:200px auto;
}
</style>
