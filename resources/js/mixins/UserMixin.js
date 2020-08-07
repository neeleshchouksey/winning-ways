
export default {
    name: 'UserMixin',
    data() {
        return {
            APP_URL: APP_URL,
            AUTH_USER: AUTH_USER,
            stateData:[],
            cityData:[],
            CategoryData:[],
            SubCategoryData:[],

        }
    },
    created(){

    },
    methods: {
        getState: function () {
            let that = this;
            axios.get(APP_URL + '/get-states').then(response => {
                    that.stateData = response.data.res;
                }
            ).catch((error) => {

            });
        },
        getCity: function () {
            let id = $("#billing_state").val();

            let that = this;
            axios.get(APP_URL + '/get-cities/' + id).then(response => {
                    that.cityData = response.data.res;
                }
            ).catch((error) => {

            });
        },
        getCategories: function () {
            let that = this;
            axios.post(APP_URL + '/select-categories').then(response => {
                    that.CategoryData = response.data.res;
                }
            ).catch((error) => {

            });
        },
        getSubCategories: function (id) {
            let that = this;
            axios.post(APP_URL + '/select-sub-categories/'+id).then(response => {
                    that.SubCategoryData = response.data.res;
                }
            ).catch((error) => {

            });
        },
    }
}
