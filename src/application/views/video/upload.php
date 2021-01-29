<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 2/28/2019
 * Time: 09:37
 */
$response = (new \youvids\application\controllers\VideoController())->upload();

$content = <<<HTML
                <div class="column">
                    <div class="message">{$response->flash()}</div>
                    <form id="frmUpload" action="{$response->request()->phpself}" method="POST" enctype="multipart/form-data">
                        <div class='form-group'>
                            <input type='file' class='form-control-file' id='videoFile' name='videoFile' value="{$response->videoFile->get()}" accept="video/*" required>
                            <span class="error">{$response->videoFile->error()}</span>
                        </div>
                        <div class='form-group'>
                            <input type='text' class='form-control' id='title' name='title' value="{$response->title->get()}" placeholder='Title' required>
                            <span class="error">{$response->title->error()}</span>
                        </div>
                        <div class='form-group'>
                            <textarea class='form-control' id='description' name='description' placeholder='Description' rows='3'>{$response->description->get()}</textarea>
                            <span class="error">{$response->description->error()}</span>
                        </div>
                        <div class='form-group'>
                            <select class='form-control' id='privacy' name='privacy'>
                                <option value='1'>Private</option>
                                <option value='2'>Public</option>
                            </select>
                        </div>
                        <div class='form-group'>
                            <select class='form-control' id='category' name='category'>
                            {$response->param('categoryOptions')}
                            </select>
                        </div>
                        <!--<button type='submit' class='btn btn-primary' name='submit'>Save</button>-->
                        <input type="submit" name="submit" class='btn btn-primary' value="SUBMIT">
                    </form>
                </div>
    
                <script src="{$response->webroot()}/js/jquery-plugins/jquery-validation/v1.19.1/jquery.validate.min.js"></script>
                <script src="{$response->webroot()}/js/jquery-plugins/jquery-validation/v1.19.1/additional-methods.min.js"></script>
                <script src="{$response->webroot()}/js/jquery-plugins/jquery-validation/additional_rules.js"></script>
                <script>
                    $().ready(function() {
                        
                        //"use strict";
                        
                        // validate upload form on keyup and submit
                        $("#frmUpload").validate({
                            rules: {
                                videoFile: {
                                    required: true,
                                    extension: "mp4|flv|webm|mkv|vob|ogv|ogg|avi|mov|mpeg|mpg", // works with additional-mothods.js 
                                    filesize: 30000000   // 30MB
                                },
                                title: {
                                    required: true,
                                    maxlength: 50
                                },
                                description: "required",
                                privacy: "required",
                                category: "required"
                            },
                            messages: {
                                videoFile: {
                                    required: "Please select your video",
                                    filesize: "Size of video must be less than 30 mb."
                                },
                                title: {
                                    required: "Please select a video",
                                    maxlength: "The title must not exceed 50 characters"
                                }
                            },
                            submitHandler: function() {
                                $("#loadingModal").modal("show");
                                $('#frmUpload').submit();
                            }
                        });
                    });
                </script>
            
                <!-- Modal -->
                <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body" style="align: center">
                                <img src="{$response->webroot()}/images/icons/loading-spinner.gif" alt="">
                                <div>Please wait. This might take a while.</div>
                            </div>
                        </div>
                    </div>
                </div>      
HTML;

require_once(ROOT_DIR . "/src/application/views/layouts/main_layout.inc.php");
?>


