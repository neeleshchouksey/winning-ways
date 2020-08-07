<template>

    <div class="contact-wrapper">
        <div class="contact-wrapper-inner">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="contact-form-wrapper">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="contact-title">
                                        <h6>Forget Password</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="post-comment-form-group">
                                        <form
                                            class="mt-5 woocomerce-form woocommerce-form-login login register-form outer-top-xs">
                                            <div
                                                class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
                                                <label for="npassword" class="info-title">Enter New Password&nbsp;<span class="required">*</span></label>
                                                <input type="password"  name="email" id="npassword" value="" v-model="forgetData.npassword" autocomplete="fo-password" class="woocommerce-Input woocommerce-Input--text input-text form-control unicase-form-control">
                                            </div>
                                            <div
                                                class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
                                                <label for="cpassword" class="info-title">Enter New Password&nbsp;<span
                                                    class="required">*</span></label>
                                                <input type="password" name="email" id="cpassword" value=""
                                                       v-model="forgetData.cpassword" autocomplete="fo-password"
                                                       class="woocommerce-Input woocommerce-Input--text input-text form-control unicase-form-control">
                                            </div>

                                            <div class="form-row">
                                                <button v-on:click="resetpassword" type="button" name="login" class="btn btn-primary">
                                                    Reset Password
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="askAccount-wrapper text-center">
                                        <a :href="APP_URL+'/signup'">Haven't An Account?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>







    </div>


</template>
<script>
    import UserMixin from "../mixins/UserMixin";

    export default {
        name: 'Product',
        data() {
            return {
                forgetData:{"npassword":"","cpassword":"","token":""}

            }
        },
        created() {
            let that = this;
            var url = document.URL.split('/')[document.URL.split('/').length - 1];
            console.log(url);
            that.forgetData.token = url;
        },
        mixins: [UserMixin],
        methods: {

            resetpassword: function () {
                let that = this;
                if(that.forgetData.npassword==""){
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Please Enter New Password",
                        showConfirmButton: true
                    });
                }else if(that.forgetData.cpassword==""){
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Please Enter Confirm Password",
                        showConfirmButton: true
                    });
                }else if(that.forgetData.npassword != that.forgetData.cpassword){
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Password not matched",
                        showConfirmButton: true
                    });
                }else {
                    axios.post(APP_URL + '/reset-password', that.forgetData).then(response => {
                            this.$swal({
                                type: "success",
                                title: "Success",
                                text: "Password Changed Successfully",
                                showConfirmButton: true
                            }).then(function () {
                                window.location = APP_URL;
                            });
                        }
                    ).catch((error) => {
                        this.$swal({
                            type: "error",
                            title: "error",
                            text: error.response.data.message,
                            showConfirmButton: true
                        });
                    });
                }
            },

        }

    }
</script>

