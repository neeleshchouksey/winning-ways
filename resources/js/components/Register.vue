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
                                        <h6>Create Your Account</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="post-comment-form-group">
                                        <form action="#" method="post">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" name="name" placeholder="Name*"
                                                           class="form-control" v-model="signupUser.name" required=""
                                                           aria-required="true">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="email" name="email" placeholder="E-mail*"
                                                           class="form-control" v-model="signupUser.email" required=""
                                                           aria-required="true">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="password" id="password" name="password" placeholder="Password*"
                                                           v-model="signupUser.password" class="form-control"
                                                           required="" aria-required="true">
                                                    <span toggle="#pwd" class="fa fa-fw field-icon toggle-password fa-eye-slash" aria-hidden="true"></span>


                                                </div>
                                                <div class="col-md-6">
                                                    <input type="password" id="rpassword" name="confirm-password"
                                                           placeholder="Confirm Password*"
                                                           v-model="signupUser.rpassword" class="form-control"
                                                           required="" aria-required="true">
                                                    <span toggle="#pwd1" class="fa fa-fw field-icon toggle-password1 fa-eye-slash" aria-hidden="true"></span>

                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select name="coupon_cat" id="billing_state" class="form-control"
                                                            v-on:change="getCity">
                                                        <option value="">Select State *</option>
                                                        <option v-for="sd in stateData" :value="sd.id">{{sd.name}}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">

<!--                                                    <select name="coupon_cat" id="billing_city" class="form-control selectpicker">-->
<!--                                                        <option value="">Select City *</option>-->
<!--                                                        <option v-for="cd in cityData" :value="cd.id">{{cd.name}}-->
<!--                                                        </option>-->
<!--                                                    </select>-->

                                                    <Dropdown
                                                        :options=cityData
                                                        :disabled="false"
                                                        name="city"
                                                        :maxItem="10"
                                                        v-on:selected="selectValue"
                                                        placeholder="Please select an option">
                                                    </Dropdown>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" maxlength="10"
                                                               @input="signupUser.phone = signupUser.phone.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1')"
                                                               v-model="signupUser.phone" placeholder="Contact No*">
                                                        <div class="input-group-btn btn-align">
                                                            <button class="btn btn-block btn-brand btn-send-otp" id="send-otp-btn"
                                                                    type="button" v-on:click="sendOtp">
                                                                Send OTP
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" name="otp" placeholder="OTP*"
                                                           v-model="signupUser.otp" class="form-control" required=""
                                                           aria-required="true">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" name="pin" placeholder="Pin-Code*" maxlength="6"
                                                           @input="signupUser.pinCode = signupUser.pinCode.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1')"
                                                           v-model="signupUser.pinCode" class="form-control" required=""
                                                           aria-required="true">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input name="Address" class="form-control" placeholder="Address*"
                                                           v-model="signupUser.address" required=""
                                                           aria-required="true">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="acceptTerms" type="checkbox">
                                                        <label for="acceptTerms">
                                                            Accept
                                                        </label>
                                                        <a href="javascript:void(0)">Terms &amp; Condition</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6"></div>
                                                <div class="col-md-6">
                                                    <input type="button" value="Sign Up" v-on:click="validateForm" :disabled="signupUser.disabled"
                                                           class="btn btn-block btn-brand btn-lg">
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="askAccount-wrapper text-center">
                                        <a :href="APP_URL+'/signin'">Have An Account?</a>
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
<style>
    .btn-align {
        vertical-align: top !important;
    }

    .btn-send-otp {
        height: 40px !important;
    }

</style>

<script>
    import UserMixin from "../mixins/UserMixin";
    export default {
        name: 'Register',
        data() {
            return {
                signupUser: {
                    "name": "",
                    "phone": "",
                    "email": "",
                    "password": "",
                    "rpassword": "",
                    "userType": 1,
                    "otp": "",
                    "address": "",
                    "city": "",
                    "state": "",
                    "pinCode": "",
                    "disabled": false
                },
            }
        },
        created() {
            $(document).ready(function(){
                $(".toggle-password").click(function() {

                    $(this).toggleClass("fa-eye fa-eye-slash");
                    var input = $("#password");
                    if (input.attr("type") == "password") {
                        input.attr("type", "text");
                    } else {
                        input.attr("type", "password");
                    }
                });
            });
            $(document).ready(function(){
                $(".toggle-password1").click(function() {
                    $(this).toggleClass("fa-eye fa-eye-slash");
                    var input = $("#rpassword");
                    if (input.attr("type") == "password") {
                        input.attr("type", "text");
                    } else {
                        input.attr("type", "password");
                    }
                });
            });

            let that = this;
            that.getState();
        },
        mixins: [UserMixin],
        methods: {
            selectValue: function (e) {
                let that = this;
              that.signupUser.city = e.id;
            },
            validateForm: function () {
                let that = this;
                if (!that.signupUser.name) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Name is required",
                        showConfirmButton: true
                    });
                } else if (!isNaN(that.signupUser.name)) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Name is not valid",
                        showConfirmButton: true
                    });
                } else if (!that.signupUser.email) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Email is required",
                        showConfirmButton: true
                    });
                } else if (!that.signupUser.password) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Password is required",
                        showConfirmButton: true
                    });
                } else if (!that.signupUser.rpassword) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Confirm Password is required",
                        showConfirmButton: true
                    });
                } else if (that.signupUser.password != that.signupUser.rpassword) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Password not matched",
                        showConfirmButton: true
                    });
                } else if (!$("#billing_state").val()) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "State is required",
                        showConfirmButton: true
                    });
                } else if (!that.signupUser.city) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "City is required",
                        showConfirmButton: true
                    });
                } else if (!that.signupUser.phone) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Phone is required",
                        showConfirmButton: true
                    });
                } else if (!that.signupUser.otp) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Otp is required",
                        showConfirmButton: true
                    });
                } else if (!that.signupUser.pinCode) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Pin Code is required",
                        showConfirmButton: true
                    });
                } else if (isNaN(that.signupUser.pinCode)) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Please Enter Valid Pin Code",
                        showConfirmButton: true
                    });
                } else if (that.signupUser.pinCode.length != 6) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Please Enter 6 digit Pin Code",
                        showConfirmButton: true
                    });
                } else if (!that.signupUser.address) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Address is required",
                        showConfirmButton: true
                    });
                } else if (!$("#acceptTerms").prop("checked")) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Please accept terms and conditions",
                        showConfirmButton: true
                    });
                } else {
                    that.validateOtp();
                }

            },
            signup: function () {
                let that = this;
                var cur_url = document.URL.split("?");
                cur_url = decodeURIComponent(cur_url[1]).split("url=");
                cur_url = cur_url[1];

                that.signupUser.state = $("#billing_state").val();
              //  that.signupUser.city = $("#billing_city").val();

                that.signupUser.disabled = true;
                axios.post(APP_URL + '/signup', that.signupUser).then(response => {
                    that.signupUser.disabled = false;
                    response = response.data;

                    this.$swal({
                        type: "success",
                        title: "Success",
                        text: "You have registered successfully",
                        showConfirmButton: true
                    }).then(function () {
                        if (cur_url) {
                            window.location = cur_url;
                        } else {
                            window.location = APP_URL;
                        }
                    });


                }).catch((error) => {
                    that.signupUser.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });

                });
            },
            sendOtp: function () {
                let that = this;
                if (!that.signupUser.phone) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Phone is required",
                        showConfirmButton: true
                    });
                } else {
                    axios.post(APP_URL + '/send-otp', {
                        'phone': that.signupUser.phone,
                    }).then(response => {
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: "OTP Sent successfully",
                            showConfirmButton: true
                        }).then(function () {
                            $("#send-otp-btn").html("Resend OTP");

                        });
                    }).catch((error) => {
                        this.$swal({
                            type: "error",
                            title: "error",
                            text: error.response.data.message,
                            showConfirmButton: true
                        });
                    });
                }
            },
            validateOtp: function () {
                let that = this;
                if (!that.signupUser.otp) {
                    that.signupError.otp = "OTP is required";
                } else {
                    axios.post(APP_URL + '/check-otp', {
                        'phone': that.signupUser.phone,
                        'otp': that.signupUser.otp,
                    }).then(response => {
                        that.signup();
                    }).catch((error) => {
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
