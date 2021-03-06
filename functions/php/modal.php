<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="modal fade" id="my_modal" tabindex="-1" role="dialog" aria-labelledby="modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-title text-center">
                    <h4 id="modal_title"></h4>
                </div>
                <div class="d-flex flex-column text-center">
                    <form id="my_form">
                        <div class="form-group">
                            <input type="email" class="form-control" autocomplete="off"
                                   id="email" placeholder="Your email address...">
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control" autocomplete="off"
                                   id="password" placeholder="Your password...">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" 
                                   id="confirm_password" placeholder="Confirm password...">
                        </div>


                        <div class="form-group" id="accept_div">
                            <a href="terms.php" id="show" target="_blank">I have read and accept the terms of use</a>
                            <p><label>Accept Terms</label>
                                <input type="checkbox" id="checkbox_accept" >
                            </p> 
                        </div>

                        <span id="status"></span>
                        <button type="button" class="btn btn-info btn-block btn-round"  
                                id="modal_button"></button>
                        <div class="form-group" id="forgot_password_div">    
                            <br>
                            <p id="forgot_password" style="text-decoration: underline;">Forgot your Password?</p>
                        </div> 
                    </form>





                </div>
            </div>

        </div>
    </div>
</div>