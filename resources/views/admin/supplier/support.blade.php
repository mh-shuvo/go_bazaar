@extends("layouts.admin")
@section("title","Client List")
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('public/admin/assets/css/support.css') }}">
<style type="text/css">
    .timeline::before {
        background-color: rgba(122,125,132,.3);
        bottom: 0;
        content: "";
        left: 7.3%;
        position: absolute;
        top: 12px;
        width: 2px;
        z-index: 0;
    }
</style>
@endsection
@section("content")
<!-- Start container-fluid -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div>
                <h4 class="header-title mb-3"><i class="mdi mdi-headphones"></i> সাপোর্ট</h4>
            </div>
        </div>
    </div>
    <!-- end row -->
     <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">

        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <h3 style="font-size: 20px;color: #fff;background-color: rgba(38,185,154,.88);border-color: rgba(38,185,154,.88);padding: 15px;margin-bottom: 20px;border: 1px solid transparent;border-radius: 4px;" class=""> আপনার যেকোন তথ্য অথবা সেবা পেতে  নিম্ন বর্ণিত  সাপোর্ট সমূহে যোগাযোগ করুন</h3>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-sm-12 col-md-6 mb-30">
                <div class="card">
                    <div class="card-header">
                        <span class="icon-copy ti-headphone-alt"></span> অফিসিয়াল সাপোর্ট সমূহ
                    </div>
                    <div class="card-body">
                        <div class="timeline mb-30 mb_margin">
                            <ul>
                                <li>
                                    <div class="timeline-date">
                                        <i class="fa fa-mobile"></i> মোবাইল
                                    </div>
                                    <div class="timeline-desc bg-white border-radius-4 box-shadow">
                                        <div class="pd-20">
                                            <h4 class="mb-10">যোগাযোগের নাম্বার সমূহ </h4>
                                            <span class="badge badge-secondary">01310-027292</span> <span class="badge badge-secondary">01319-081656</span>
                                            <span class="badge badge-secondary">01714-049026</span>
                                            <span class="badge badge-secondary"> 01714-049013</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="timeline-date">
                                        <i class="mdi mdi-email"></i> ই-মেইল
                                    </div>
                                    <div class="timeline-desc bg-white border-radius-4 box-shadow">
                                        <div class="pd-20">
                                            <h4 class="mb-10">ইমেইল করুন </h4>
                                            <span class="badge badge-pill badge-light">support@dgins.gov.bd</span>
                                            <span class="badge badge-pill badge-light">support@innovationit.com.bd</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="timeline-date">
                                        <i class="mdi mdi-facebook"></i> Facebook
                                    </div>
                                    <div class="timeline-desc bg-white border-radius-4 box-shadow">
                                        <div class="pd-20">
                                            <h4 class="mb-10">ফেসবুক পেইজ </h4>
                                            <a target="_blank" href="https://www.facebook.com/messages/t/3095292177214835" type="button" class="badge p-2" data-bgcolor="#3b5998" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(59, 89, 152);">নরসিংদী Go Bazaar</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 mb-30">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-download"></i> ডাউনলোড করুন
                    </div>
                    <div class="card-body">

                        <ul class="list-group">
                            <li class="list-group-item">
                                <a href="https://anydesk.com/en"><img class="list_image" src="{{ asset('public/admin/assets/images/anydesk-logo.png') }}"> <i class="fa fa-download font"> ডাউনলোড</i></a>
                            </li>
                            <li class="list-group-item">
                                <a href="https://www.skype.com/en/"> <img class="list_image" src="{{ asset('public/admin/assets/images/skype-logo.png') }}"> <i class="fa fa-download font"> ডাউনলোড</i></a>
                            </li>
                            <li class="list-group-item">
                                <a href="https://www.teamviewer.com/en/"><img class="list_image" src="{{ asset('public/admin/assets/images/teamviewer-logo.png') }}"> <i class="fa fa-download font"> ডাউনলোড</i></a>
                            </li>
                            <li class="list-group-item">
                                <a href="javascript:void(0)"><img class="list_image_user" src="{{ asset('public/admin/assets/images/user_menual.png') }}">  ইউজার গাইড  <i class="fa fa-download font"> ডাউনলোড</i></a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>

            <hr>

            <div class="col-md-6 col-xs-12 widget widget_tally_box" >
                <div class="x_panel ui-ribbon-container fixed_height_390">
                    <div class="ui-ribbon-wrapper">
                        <div class="ui-ribbon">
                            Dhaka
                        </div>
                    </div>
                    <div class="x_title">
                        <h2>Head Office</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div style="text-align: center; margin-bottom: 17px">
                            <img src="{{asset('public/logo/logo_iit.png')}}" class="img-responsive logo_img">
                        </div>
                        <br>
                        <h3 class="name_title" style="font-size: 17px"><i class="fa fa-home"></i>&nbsp; অফিস লোকেশন </h3>

                        <div class="divider"></div>
                        <ul class="list-inline widget_tally">
                            <li>
                                <p style="font-size: 15px">
                                    <i class="fa fa-home"></i> House# 1/10, Mayer anchal Building,5<sup>th</sup> Floor Block-A, Lalmatia,Mohammadpur, Dhaka - 1207, Bangladesh
                                </p>
                            </li>
                            <li>
                                <p>
                                    <i class="fa fa-phone"></i> 01633-036189,01633-036190
                                </p>
                            </li>
                            <li>
                                <p>
                                    <i class="fa fa-envelope"></i> support@innovationit.com.bd
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xs-12 widget widget_tally_box">
                <div class="x_panel ui-ribbon-container fixed_height_390">
                    <div class="ui-ribbon-wrapper">
                        <div class="ui-ribbon">
                            Cumilla
                        </div>
                    </div>
                    <div class="x_title">
                        <h2>Branch Office</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div style="text-align: center; margin-bottom: 17px">
                            <img src="{{asset('public/logo/logo_iit.png')}}" class="img-responsive logo_img">
                        </div>
                        <br>
                        <h3 class="name_title" style="font-size: 17px"><i class="fa fa-home"></i>&nbsp; অফিস লোকেশন </h3>

                        <div class="divider"></div>
                        <ul class="list-inline widget_tally">
                            <li>
                                <p style="font-size: 15px">
                                    <i class="fa fa-home"></i> House no-211/29, Ayesha Monjil-2,3<sup>rd</sup> Floor, Niloy Society, West Bagichagaon, Station Road, Cumilla-3500, Bangladesh
                                </p>
                            </li>
                            <li>
                                <p>
                                    <i class="fa fa-phone"></i> 01647-534662,01310-027292,01319-081656
                                </p>
                            </li>
                            <li>
                                <p>
                                    <i class="fa fa-envelope"></i> support@innovationit.com.bd
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

</div>
<!-- end container-fluid -->

@endsection
