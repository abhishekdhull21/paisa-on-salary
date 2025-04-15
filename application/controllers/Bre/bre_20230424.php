
<style>
    .bre_result_container{
        width: 100%;
        float: left;
        position: relative;
        padding: 10px;
        background-color: #fff;
        margin: 2% 0;
        border-style: solid;
        border-color: #eee;
        border-width: 3px 3px 2px 0;
        border-radius: 0 37px 37px 0;
        background: linear-gradient(to right, rgb(255 255 255 / 0%) 0%,rgb(255 255 255) 50%,rgb(255 255 255) 100%);
    }
    .bre_result_container details summary::-webkit-details-marker {
        display: none;
    }
    .bre_result_container summary::before {
        font-family: "Hiragino Mincho ProN", "Open Sans", sans-serif;
        content: "▶";
        position: absolute;
        top: 1rem;
        right: 1.8rem;
        font-size: 25px;
        transform: rotate(0);
        transform-origin: center;
        transition: 0.2s transform ease;
        color: #fff;
    }
    .bre_result_container details[open] > summary:before {
        transform: rotate(90deg);
        transition: 0.45s transform ease;
    }
    #bre .tab-content #LeadBREResultInner p{
        background-color: #fff;
        transition:all .3s ease-in-out;
        width: 100%;
        float: left;
    }
    .bre_result_container details {
        /* overflow: hidden;   */
        -webkit-transition: max-height 0.5s ease;
        transition: max-height 0.5s ease;
        transition-property: all;
        transition-delay: .2s;
        transition-duration: .2s;
        transition-timing-function: ease-in;
    }
    .tab .nav-tabs li a{
        margin-right: 2px !Important;
    }
    .bre_result_container details summary {
        position: relative;
        z-index: 10;

    }
    @keyframes details-show {
        from {
            margin-bottom: -80%;
            opacity: 0;
            transform: translateY(-100%);
        }
    }
    .bre_result_container details > *:not(summary) {
        /*animation: details-show 500ms ease-in-out;*/
        position: relative;
        z-index: 1;
        transition: all 0.3s ease-in-out;
        color: transparent;
        /* overflow: hidden; */
        -webkit-transition: all 0.5s ease-in-out;
        -moz-transition: all 0.5s ease-in-out;
        -ms-transition: all 0.5s ease-in-out;
        transition: all 0.5s ease-in-out;




    }
    .bre_result_container details[open] > *:not(summary) {
        color: inherit;
    }


    .bre_result_container details.bre_result_category summary {
        padding-right: 2.2rem;
        padding-left: 0;
        background: #eee;
        box-shadow: none;
        backdrop-filter: blur(10px);
        border-radius: 12px 28px 28px 12px;
        margin-bottom: 0;
        background: #ffffff;
        background: linear-gradient(45deg, #9bbcd8 0%, #7e9bde 25%, #ef5c82 51%, #f15882 100%);
        z-index: 1;
        -webkit-transition: -webkit-transform .4s ease;
        transition: -webkit-transform .4s ease;
        transition: transform .4s ease;
        transition: transform .4s ease, -webkit-transform .4s ease;
        padding: 18px 0;
        box-shadow: -4px -5px 0px rgba(13, 39, 80, 0.25), -10px -10px 15px white;
        margin-bottom: 10px;
    }



    .bre_result_container details.bre_result_category summary:hover{
        -webkit-transform: translateX(-4%);
        transform: translateX(-4%);
        z-index: 1;
        -webkit-transition: -webkit-transform .4s ease;
        transition: -webkit-transform .4s ease;
        transition: transform .4s ease;
        transition: transform .4s ease, -webkit-transform .4s ease;
    }


    .bre_result_container details.bre_result_category summary::before {

    }



    #bre .tab-content #LeadBREResultInner p span img{
        height: auto;
        width: 21px;
        float: right;
    }



    .bre_result_container details[open].bre_result_category > summary:before {
        content: "✓";
        transform: rotate(0deg);
    }

    .bre_result_container detai
    .bre_result_container img {
        max-width: 100%;
    }
    .bre_result_container p {
        margin: 0;
        padding-bottom: 1px;
    }
    .bre_result_container p:last-child {
        padding: 0;
    }
    .bre_result_container details {
        max-width: 100%;
        box-sizing: border-box;
        margin-top: 5px;
    }





    .bre_result_container table tr th, td {
        width: auto;
    }
    .bre_result_container summary {
        outline: none;

        display: block;
        background: #fff;
        color: #000;
        padding-left: 0;
        position: relative;
        margin-bottom: 0;
        cursor: pointer;
        background: linear-gradient(135deg, rgb(233 233 233) 43%,rgb(248 248 248) 41%,rgb(233 233 233) 35%,rgb(208 205 205) 100%);
        font-size: 14px;
        text-transform: capitalize;
        border-radius: 0;
        letter-spacing: 1px;
        text-shadow: none;
    }
    .bre_result_container summary span{
        background: #f3f3f347;
        transform: translate(-50%, -51%);
        border-radius: inherit;
        box-shadow: 12px 12px 32px rgba(13, 39, 80, 0.25), -10px -10px 15px white;
        padding: 16px 2%;
    }

    .bre_result_container summary span::after{
        content: url(../images/vies2.png);
        display: inline-block;
        width: 87px;
        height: auto;
        margin-right: 5px;
        /* background-size: contain; */
        /* background: url(../images/vies.PNG); */
        background-position: bottom;
        background-size: contain;
        background-repeat: no-repeat !important;
        bottom: -23px;
        z-index: -15;
        position: absolute;
        left: 8em;

        font-size: 38px;
        transform: rotate(36deg);
        position: absolute;



    }


    .bre_result_container .bre_result_category_name span::after{
        left: 30em;
        width: 100%;
    }
    .bre_result_container summary:hover strong,
    details[open] summary strong,
    summary:hover::before,
    details[open] summary::before {
        color: #fff;
    }
    .bre_result_container .content {
        padding: 16px;
        border: none;
        border-top: none;
        background-color: #fff;
        border-radius: 20px;
        margin-top: 0;
    }






    .bre_result_container table{
        width: 100%;
        background-color: #fff;
    }
    .bre_result_container tr>th {
        border: solid 2px #fff;
        padding: 10px;
        background-color: #7c8fd0;
        color: #fff;
        font-weight: 400;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 11px;
        text-align: center;
        box-shadow: 0 8px 32px 0 rgb(31 38 135 / 17%);
        backdrop-filter: blur(10px);
        /*background: linear-gradient(135deg, rgb(2 24 123) 40%,rgb(17 78 176) 41%,rgb(9 61 152) 35%,rgb(4 37 107) 100%);*/
    }
    .bre_result_container tr>td{
        border: dotted 1px #c6c1c1;
        padding: 5px 7px;
        font-size: 11px;
        color: #000;
        font-weight: 500;
    }


    .bre_result_container  .main_mdl1 p{
        font-size: 11px;
        border-bottom: solid 1px #eee;
        padding: 5px 0;
    }
    .bre_result_container  svg {
        width: 31px;
        display: block;
        margin: 0 auto 0;
        float: right;
    }
    .bre_result_container  .path {
        stroke-dasharray: 1000;
        stroke-dashoffset: 0;
    }
    .bre_result_container  .path.circle {
        -webkit-animation: dash .5s ease-in-out;
        animation: dash .5s ease-in-out;
    }
    .bre_result_container  .path.line {
        stroke-dashoffset: 1000;
        -webkit-animation: dash 9s .35s ease-in-out forwards;
        animation: dash 9s .35s ease-in-out forwards;
    }
    .bre_result_container  .path.check {
        stroke-dashoffset: -100;
        -webkit-animation: dash-check 9s .35s ease-in-out forwards;
        animation: dash-check 9s .35s ease-in-out forwards;
    }

    @-webkit-keyframes dash {
        0% {
            stroke-dashoffset: 1000;
        }
        100% {
            stroke-dashoffset: 0;
        }
    }
    @keyframes dash {
        0% {
            stroke-dashoffset: 1000;
        }
        100% {
            stroke-dashoffset: 0;
        }
    }
    @-webkit-keyframes dash-check {
        0% {
            stroke-dashoffset: -100;
        }
        100% {
            stroke-dashoffset: 900;
        }
    }
    @keyframes dash-check {
        0% {
            stroke-dashoffset: -100;
        }
        100% {
            stroke-dashoffset: 900;
        }
    }
</style>
<div class="tab-content tabs">
    <div role="tabpanel" class="tab-pane fade in active" id="LeadBREResult">                   

        <div id="LeadBREResultInner">

            <?php if (in_array($leadDetails->lead_status_id, array(5, 6, 11)) && ((agent == 'CR2' && $leadDetails->lead_credit_assign_user_id == user_id) || agent == 'CA')) { ?>
                <p>BRE Requirement  : Please ensure all the data points and apis has been called before run the BRE. If not then you will get the rejection and repetition of work leads to lower productivity.
                    <br/><button onclick="call_bre_rule_engine()" class="btn btn-success lead-sanction-button">RUN BRE</button></p>
            <?php } ?>
            <div class="bre_result_container" id="bre_rule_result_container"> 


            </div>
        </div>
    </div>
</div>
