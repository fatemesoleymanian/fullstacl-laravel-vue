
export default {
    data(){
        return {

        }
    },
    methods: {
        async callApi(method, url, dataObj) {
            try {
                return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                });
            } catch (e) {
                return e.response
            }
        },

        s(desc, title = "Great!") {
            this.$Notice.success({
                title: title,
                desc: desc
            });
        },
        swr(desc = 'Something went wrong! Please try again.', title = "Oops") {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },
        w(desc, title = "Oops!") {
            this.$Notice.warning({
                title: title,
                desc: desc
            });
        },

        error (nodesc,name) {
            this.$Notice.error({
                title: 'Oops!',
                desc: nodesc ? `${name} required!` : ''
            });
        },
        i(desc, title = "Hey") {
            this.$Notice.info({
                title: title,
                desc: desc
            });
        },

    }
}
