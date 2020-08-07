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
                                        <h6>Sign In Your Account</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="post-comment-form-group">
                                        <form action="#" method="post">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="email" name="email" v-model="loginUser.email"
                                                           placeholder="Email or Mobile*" class="form-control"
                                                           required=""
                                                           aria-required="true">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="password" name="password" id="password"
                                                           v-model="loginUser.password"
                                                           placeholder="Password*" class="form-control" required=""
                                                           aria-required="true">
                                                    <span toggle="#pwd"
                                                          class="fa fa-fw field-icon toggle-password fa-eye-slash"
                                                          aria-hidden="true"></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="button" value="Sign In"
                                                           class="btn btn-block btn-brand btn-lg"
                                                           :disabled="loginUser.disabled" v-on:click="login">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <a href="javascript:void(0)" class="pull-right" data-toggle="modal"
                                       data-target="#forgetten_pass">Forgot Password?</a>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="login-wrapper text-center">
                                        <p><span>or</span> login with</p>
                                        <div class="login-with-social">
                                            <ul>
                                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                                <!--                                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>-->
                                                <li><a href="#"><i class="fa fa-google"></i></a></li>
                                            </ul>
                                        </div>
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


        <div id="forgetten_pass" class="modal fade" role="dialog">
            <div class="modal-dialog modal-md">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header th-modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title th-modal-title">Forgot Password</h6>
                    </div>
                    <div class="modal-body">
                        <div class="coupon-modal-content">
                            <div class="row" id="r-pass">
                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <input type="email" v-model="forgetPasswordData.email" class="form-control"
                                               placeholder="Enter Your E-mail Id/Phone">
                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <button class="btn btn-primary" v-on:click="sendEmail"
                                            :disabled="forgetPasswordData.disabled">Send
                                    </button>
                                </div>
                            </div>

                            <form id="otp-form" style="display: none"
                                  class="mt-5 woocomerce-form woocommerce-form-login login register-form outer-top-xs">
                                <div
                                    class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
                                    <label for="npassword" class="info-title">Enter OTP&nbsp;<span
                                        class="required">*</span></label>
                                    <input type="text" name="email" id="otp" value=""
                                           v-model="forgetPasswordData.otp" autocomplete="fo-password"
                                           class="woocommerce-Input woocommerce-Input--text input-text form-control unicase-form-control">
                                </div>
                                <div
                                    class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
                                    <label for="npassword" class="info-title">Enter New Password&nbsp;<span
                                        class="required">*</span></label>
                                    <input type="password" name="email" id="npassword" value=""
                                           v-model="forgetPasswordData.npassword" autocomplete="fo-password"
                                           class="woocommerce-Input woocommerce-Input--text input-text form-control unicase-form-control">
                                </div>
                                <div
                                    class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
                                    <label for="cpassword" class="info-title">Enter New Password&nbsp;<span
                                        class="required">*</span></label>
                                    <input type="password" name="email" id="cpassword" value=""
                                           v-model="forgetPasswordData.cpassword" autocomplete="fo-password"
                                           class="woocommerce-Input woocommerce-Input--text input-text form-control unicase-form-control">
                                </div>

                                <div class="form-row">
                                    <button v-on:click="resetpassword" type="button" name="login"
                                            class="btn btn-primary">
                                        Reset Password
                                    </button>
                                </div>
                            </form>

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
        name: 'Login',
        data() {
            return {
                loginUser: {
                    "email": "",
                    "password": "",
                    "userType": 1,
                    "disabled": false
                },
                forgetPasswordData: {
                    "email": "",
                    "disabled": false
                },
            }
        },
        created() {
            $(document).ready(function () {
                $(".toggle-password").click(function () {

                    $(this).toggleClass("fa-eye fa-eye-slash");
                    var input = $("#password");
                    if (input.attr("type") == "password") {
                        input.attr("type", "text");
                    } else {
                        input.attr("type", "password");
                    }
                });
            });
        },
        mixins: [UserMixin],
        methods: {

            resetpassword: function () {
                let that = this;
                if (that.forgetPasswordData.npassword == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Please Enter New Password",
                        showConfirmButton: true
                    });
                } else if (that.forgetPasswordData.cpassword == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Please Enter Confirm Password",
                        showConfirmButton: true
                    });
                } else if (that.forgetPasswordData.npassword != that.forgetPasswordData.cpassword) {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Password not matched",
                        showConfirmButton: true
                    });
                } else {
                    axios.post(APP_URL + '/reset-password-otp', that.forgetPasswordData).then(response => {
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

            sendEmail: function () {
                let that = this;

                if (that.forgetPasswordData.email == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Please Enter Email",
                        showConfirmButton: true
                    });
                } else if (!isNaN(that.forgetPasswordData.email)) {
                    that.sendOtp();
                } else {
                    axios.post(APP_URL + '/send-email', that.forgetPasswordData).then(response => {
                            that.forgetPasswordData.disabled = true;
                            this.$swal({
                                type: "success",
                                title: "Success",
                                text: response.data.message,
                                showConfirmButton: true
                            }).then(function () {
                                that.forgetPasswordData.email = "";
                                that.forgetPasswordData.disabled = false;
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
            sendOtp: function () {
                let that = this;
                axios.post(APP_URL + '/send-forget-otp', {
                    'phone': that.forgetPasswordData.email,
                }).then(response => {
                    this.$swal({
                        type: "success",
                        title: "Success",
                        text: "OTP Sent successfully",
                        showConfirmButton: true
                    }).then(function () {
                        $("#r-pass").css("display", "none");
                        $("#otp-form").css("display", "block");
                    });
                }).catch((error) => {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            login: function () {
                let that = this;
                var cur_url = document.URL.split("?");
                cur_url = decodeURIComponent(cur_url[1]).split("url=");
                cur_url = cur_url[1];

                if (that.loginUser.email == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Please Enter Email or Mobile",
                        showConfirmButton: true
                    });
                } else if (that.loginUser.password == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Please Enter Password",
                        showConfirmButton: true
                    });
                } else {
                    that.loginUser.disabled = true;
                    axios.post(APP_URL + '/login', that.loginUser).then(response => {
                            if (cur_url) {
                                window.location = cur_url;
                            } else {
                                window.location = APP_URL;
                            }
                        }
                    ).catch((error) => {
                        that.loginUser.disabled = false;
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
