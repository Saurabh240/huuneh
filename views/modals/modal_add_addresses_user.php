<!-- Modal -->
<div class="modal fade" id="myModalAddUserAddresses" role="dialog" aria-labelledby="modal_add_address_title">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo $lang['modal-text3'] ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="addSenderAddressTab" data-toggle="tab" href="#addSenderAddress" role="tab">Add Sender Address</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="howToAddressTab" data-toggle="tab" href="#howToAddress" role="tab">How to</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content mt-3">
                    <!-- Add Sender Address Tab Content -->
                    <div class="tab-pane fade show active" id="addSenderAddress" role="tabpanel">
                        <form class="form-horizontal" method="post" id="add_address_users_from_modal_shipments" name="add_address_users_from_modal_shipments">
                            <!-- Existing content for adding sender address -->
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address_modal_user_address"><?php echo $lang['user_manage10'] ?></label>
                                        <input type="text" class="form-control" name="address_modal_user_address" id="address_modal_user_address" placeholder="<?php echo $lang['user_manage10'] ?>">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $lang['leftorder318'] ?></label>
                                        <select style="width: 100% !important;" class="select2 form-control" name="country_modal_user_address" id="country_modal_user_address">
                                            <!-- Populate with options -->
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class=""><?php echo $lang['leftorder319'] ?></label>
                                        <select style="width: 100% !important;" disabled class="select2 form-control" id="state_modal_user_address" name="state_modal_user_address">
                                            <!-- Populate with options -->
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class=""><?php echo $lang['leftorder320'] ?></label>
                                        <select style="width: 100% !important;" disabled class="select2 form-control" id="city_modal_user_address" name="city_modal_user_address">
                                            <!-- Populate with options -->
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="postal_modal_user_address"><?php echo $lang['user_manage14'] ?></label>
                                        <input type="text" class="form-control" name="postal_modal_user_address" id="postal_modal_user_address" placeholder="<?php echo $lang['user_manage14'] ?>">
                                    </div>
                                </div>
                            </div>
                            <!-- Add sender address-specific buttons -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success" id="save_data_address_users"><?php echo $lang['modal-text6'] ?></button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $lang['modal-text5'] ?></button>
                            </div>
                        </form>
                    </div>

                    <!-- How to Tab Content -->
                    <div class="tab-pane fade" id="howToAddress" role="tabpanel">
                        <p>To learn how to add a sender address, <a href="https://youtube.com" id="howToAddressLink">click here</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
