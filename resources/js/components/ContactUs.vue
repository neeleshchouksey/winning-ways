<template>
    <div class="contact-form-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="contact-title">
                    <h6>Contact Us</h6>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <div class="post-comment-form-group">
                    <form action="#" method="post" id="cbx-contact-form">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="cbxname" placeholder="Name*" class="form-control" required=""
                                       v-model="contactUsData.name" aria-required="true">
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="cbxemail" placeholder="Email*" class="form-control"
                                       v-model="contactUsData.email" required="" aria-required="true">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input v-model="contactUsData.phone" type="text" name="phone" placeholder="Phone"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input v-model="contactUsData.subject" type="text" name="subject" placeholder="Subject"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <textarea v-model="contactUsData.message" cols="30" name="cbxmessage" rows="6"
                                          placeholder="Message*"
                                          class="form-control" required=""></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="button" value="Send Your Message" class="btn btn-block btn-brand btn-lg"
                                       v-on:click="saveData">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-5">
                <div class="contact-hotline">
                    <div class="hotline-content">
                        <p>HOTLINE</p>
                        <p>+91 9826085023</p>
                    </div>
                    <div class="hotline-thumb">
                        <img :src="APP_URL+'/public/assets/img/contact/mr_helpline.jpg'" alt=""
                             class="img-responsive img-rounded"/>
                    </div>
                </div>




                <div class="contact-info-wrapper">
                    <h6>Address:</h6>
                    <p>30/A, Veena Nagar, Sukhliya, Indore - 452010, Near OYO Madhur Inn Hotel</p>
                    <h6>Contact Info:</h6>
                    <p><i class="fa fa-phone"></i>9826669992, <i class="fa fa-phone"></i>9826085023</p>
                    <p><i class="fa fa-envelope"></i>info@workportal.in, <i class="fa fa-globe"></i>http://workportal.in
                    </p>
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
                contactUsData: {
                    "name": "",
                    "phone": "",
                    "email": "",
                    "subject": "",
                    "message": "",
                    "disabled": false
                },
            }
        },
        created() {
            let that = this;
        },
        mixins: [UserMixin],
        methods: {
            saveData: function () {
                let that = this;

                if (!that.contactUsData.name) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Name is required",
                        showConfirmButton: true
                    });
                } else if (!isNaN(that.contactUsData.name)) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Name is not valid",
                        showConfirmButton: true
                    });
                } else if (!that.contactUsData.email) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Email is required",
                        showConfirmButton: true
                    });
                } else if (!that.contactUsData.phone) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Phone is required",
                        showConfirmButton: true
                    });
                } else if (!that.contactUsData.subject) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Subject is required",
                        showConfirmButton: true
                    });
                } else if (!that.contactUsData.message) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Message is required",
                        showConfirmButton: true
                    });
                } else {

                    that.contactUsData.disabled = true;
                    axios.post(APP_URL + '/add-contact-us', that.contactUsData).then(response => {
                        that.contactUsData.disabled = false;
                        response = response.data;

                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: "You have successfully submitted details",
                            showConfirmButton: true
                        }).then(function () {
                            window.location = APP_URL;
                        });


                    }).catch((error) => {
                        that.contactUsData.disabled = false;
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
