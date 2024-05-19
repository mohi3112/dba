@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Subscriptions /</span> Subscription Details</h4>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <h5 class="card-header">Lawyer Details</h5>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 mb-4 mb-xl-0">
                    <!-- <small class="text-light fw-semibold">Vertical</small> -->
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-3 col-12 mb-3 mb-md-0">
                                <div class="list-group">
                                    <a class="list-group-item list-group-item-action" id="list-home-list" data-bs-toggle="list" href="#basi-details">Home</a>
                                    <a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="list" href="#list-profile">Profile</a>
                                    <a class="list-group-item list-group-item-action" id="list-messages-list" data-bs-toggle="list" href="#list-messages">Messages</a>
                                    <a class="list-group-item list-group-item-action active" id="list-settings-list" data-bs-toggle="list" href="#list-settings">Settings</a>
                                </div>
                            </div>
                            <div class="col-md-9 col-12">
                                <div class="tab-content p-0">
                                    <div class="tab-pane fade" id="basi-details">
                                        <div class="row">
                                            <div class="col-md-6">NAME</div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="list-profile">
                                        Muffin lemon drops chocolate chupa chups jelly beans dessert jelly-o. Soufflé gummies
                                        gummies. Ice cream powder marshmallow cotton candy oat cake wafer. Marshmallow
                                        gingerbread tootsie roll. Chocolate cake bonbon jelly beans lollipop jelly beans halvah
                                        marzipan danish pie. Oat cake chocolate cake pudding bear claw liquorice gingerbread
                                        icing sugar plum brownie. Toffee cookie apple pie cheesecake bear claw sugar plum wafer
                                        gummi bears fruitcake.
                                    </div>
                                    <div class="tab-pane fade" id="list-messages">
                                        Ice cream dessert candy sugar plum croissant cupcake tart pie apple pie. Pastry
                                        chocolate chupa chups tiramisu. Tiramisu cookie oat cake. Pudding brownie bonbon. Pie
                                        carrot cake chocolate macaroon. Halvah jelly jelly beans cake macaroon jelly-o. Danish
                                        pastry dessert gingerbread powder halvah. Muffin bonbon fruitcake dragée sweet sesame
                                        snaps oat cake marshmallow cheesecake. Cupcake donut sweet bonbon cheesecake soufflé
                                        chocolate bar.
                                    </div>
                                    <div class="tab-pane fade active show" id="list-settings">
                                        Marzipan cake oat cake. Marshmallow pie chocolate. Liquorice oat cake donut halvah
                                        jelly-o. Jelly-o muffin macaroon cake gingerbread candy cupcake. Cake lollipop lollipop
                                        jelly brownie cake topping chocolate. Pie oat cake jelly. Lemon drops halvah jelly
                                        cookie bonbon cake cupcake ice cream. Donut tart bonbon sweet roll soufflé gummies
                                        biscuit. Wafer toffee topping jelly beans icing pie apple pie toffee pudding. Tiramisu
                                        powder macaroon tiramisu cake halvah.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection