<template>
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12 no-padding-left-right">
                    <div class="dashboard-title text-center">
                        <h6>Welcome back to workportal</h6>
                    </div>
                </div>

            </div>
            <div class="row">

                <div class="col-md-12 no-padding-left-right">
                    <div class="dashboard-wrap th-dashboard-wrap table table-responsive">

                        <table class="table" v-if="paymentData.length">

                            <thead>
                            <tr>
                            <tr>
                                <th>S. No.</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Comment</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(wd,index) in paymentData">
                                <td>{{index + 1}}</td>
                                <td>{{wd.created_at | formatDate}}</td>
                                <td>{{wd.amount}}</td>
                                <td>{{wd.comment}}</td>
                                <td><span v-if="wd.status==0">Pending</span><span v-if="wd.status==1">Accepted</span></td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination :data="paymentDataPage"
                                    @pagination-change-page="getUserPayments"></pagination>
                        <p class="no-data-foun" v-if="!paymentData.length">No Data Found</p>
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

    .noUi-connect {
        background: #3b5898 !important;
    }

</style>

<script>
    import UserMixin from "../mixins/UserMixin";

    export default {
        name: 'Register',
        data() {
            return {
                paymentData: [],
                paymentDataPage: {},
            }
        },
        created() {
            let that = this;
            that.getUserPayments();
        },
        mixins: [UserMixin],
        methods: {
            getUserPayments: function (page = 1) {
                let that = this;
                axios.get(APP_URL + '/get-debit-requests?page=' + page).then(response => {
                        that.paymentData = response.data.res.data;
                        that.paymentDataPage = response.data.res;
                    }
                ).catch((error) => {

                });
            },
        }
    }
</script>
