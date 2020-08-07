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
                    <div class="dashboard-wrap">
                        <div class="dashboard-table-attr-type">
                            <ul>
                                <li class="active"><a href="javascript:void(0)" data-toggle="modal"
                                                      data-target="#myModal">Request for
                                    Debit</a></li>
                                <li class="active"><a :href="APP_URL+'/export-user-account-statement'" >Export to Excel</a></li>

                            </ul>
                        </div>
                        <div class="table-responsive">
                        <table class="table " v-if="paymentData.length">

                            <thead>
                            <tr>
                            <tr>
                                <th>S. No.</th>
                                <th>Date</th>
                                <th>CREDIT</th>
                                <th>DEBIT</th>
                                <th>Balance</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(wd,index) in paymentData">
            <td>{{index + 1}}</td>
                                <td>{{wd.updated_at | formatDate}}</td>
                                <td><span v-if="wd.transaction_type=='CREDIT'">{{wd.amount}}</span><span v-if="wd.transaction_type=='DEBIT'">-</span></td>
                                <td><span v-if="wd.transaction_type=='DEBIT'">{{wd.amount}}</span><span v-if="wd.transaction_type=='CREDIT'">-</span></td>
                                <td>{{wd.balance}}</td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                        <pagination :data="paymentDataPage"
                                    @pagination-change-page="getUserPayments"></pagination>
                        <p class="no-data-foun" v-if="!paymentData.length">No Data Found</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header th-modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Request For Debit</h6>
                    </div>
                    <div class="modal-body  th-modal-body">
                        <div class="row post-comment-form-group">
                            <div class="col-md-12 mt-3">
                                <input type="text" name="amount" placeholder="Amount*"
                                       class="form-control" v-model="debitRequestData.amount" required=""
                                       @input="debitRequestData.amount = debitRequestData.amount.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1')"
                                       aria-required="true">
                            </div>
                            <div class="col-md-12 mt-3">
                                <textarea class="form-control" name="comment" placeholder="Comments*"
                                          v-model="debitRequestData.comment"/>
                            </div>
                        </div>

                        <div class="th-.modal-footer">
                            <button type="button" class="btn btn-primary" :disabled="debitRequestData.disabled"
                                    v-on:click="requestForDebit">Request
                            </button>
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
                debitRequestData: {
                    "amount": "",
                    "comment": "",
                    "disabled": false
                }
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
                axios.get(APP_URL + '/get-user-payments?page=' + page).then(response => {
                        that.paymentData = response.data.res.data;
                        that.paymentDataPage = response.data.res;
                    }
                ).catch((error) => {

                });
            },
            requestForDebit: function () {
                let that = this;
                if (!that.debitRequestData.amount) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Amount is required",
                        showConfirmButton: true
                    });
                }else  if (!that.debitRequestData.comment) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Comment is required",
                        showConfirmButton: true
                    });
                }else {
                    axios.post(APP_URL + '/request-for-debit', that.debitRequestData).then(response => {
                            this.$swal({
                                type: "success",
                                title: "Success",
                                text: "Successfully Requested for Debit",
                                showConfirmButton: true
                            }).then(function () {
                                window.location = APP_URL + '/debit-requests';
                            });
                        }
                    ).catch((error) => {

                    });
                }
            }

        }
    }
</script>
