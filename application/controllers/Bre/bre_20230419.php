
<style>
    .deviation_data_mdl{
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
        .deviation_data_mdl details summary::-webkit-details-marker { display: none; }
        .deviation_data_mdl summary::before {
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
        .deviation_data_mdl details[open] > summary:before {
          transform: rotate(90deg);
          transition: 0.45s transform ease;
        }
  #bre .tab-content #LeadDetails p{
    padding: 20px 20px;
    background-color: #fff;
    border: solid 1px #eee;
 transition:all .3s ease-in-out;
}
        .deviation_data_mdl details { 
          /* overflow: hidden;   */
           -webkit-transition: max-height 0.5s ease;
            transition: max-height 0.5s ease;  transition-property: all;
            transition-delay: .2s;
            transition-duration: .2s;
            transition-timing-function: ease-in;}
        .deviation_data_mdl details summary {
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
        .deviation_data_mdl details > *:not(summary) {
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
        .deviation_data_mdl details[open] > *:not(summary) { color: inherit; }


        .deviation_data_mdl details.style6 summary {
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



 .deviation_data_mdl details.style6 summary:hover{
    -webkit-transform: translateX(-4%);
    transform: translateX(-4%);
   z-index: 1;
    -webkit-transition: -webkit-transform .4s ease;
    transition: -webkit-transform .4s ease;
    transition: transform .4s ease;
    transition: transform .4s ease, -webkit-transform .4s ease;
}


        .deviation_data_mdl details.style6 summary::before {
   
        }



 


        
        .deviation_data_mdl details[open].style6 > summary:before {
          content: "✓";
          transform: rotate(0deg);
        }

        .deviation_data_mdl detai
        .deviation_data_mdl img { max-width: 100%; }
        .deviation_data_mdl p { margin: 0; padding-bottom: 1px;}
        .deviation_data_mdl p:last-child { padding: 0; }
        .deviation_data_mdl details {
                max-width: 100%;
    box-sizing: border-box;
    margin-top: 5px;
    background: 
        }

	 



        .deviation_data_mdl table tr th, td {
            width: auto;
        }
        .deviation_data_mdl summary {
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
        .deviation_data_mdl summary span{
             background: #f3f3f347;
        transform: translate(-50%, -51%);
    border-radius: inherit;
    box-shadow: 12px 12px 32px rgba(13, 39, 80, 0.25), -10px -10px 15px white;
    padding: 16px 2%;
        } 

        .deviation_data_mdl summary span::after{
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

       
        .deviation_data_mdl .second_mdl span::after{
          left: 30em;
          width: 100%;
        } 
        .deviation_data_mdl summary:hover strong,
        details[open] summary strong,
        summary:hover::before,
        details[open] summary::before {
          color: #fff;
        }
        .deviation_data_mdl .content {
                 padding: 16px;
    border: none;
    border-top: none;
    background-color: #fff;
    border-radius: 20px;
    margin-top: 0;
        }


 



        .deviation_data_mdl table{
          width: 100%;
background-color: #fff;
        }
        .deviation_data_mdl tr>th {
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
        .deviation_data_mdl tr>td{
          border: dotted 1px #c6c1c1;
          padding: 5px 7px;
          font-size: 11px;
          color: #000;
          font-weight: 500;
        }
        .deviation_data_mdl tr>td span{
          float: right;
        }

        .deviation_data_mdl  .main_mdl1 p{ font-size: 11px;
          border-bottom: solid 1px #eee;
          padding: 5px 0;
        }
        .deviation_data_mdl  svg {
          width: 31px;
          display: block;
          margin: 0 auto 0;
          float: right;
        }
        .deviation_data_mdl  .path {
          stroke-dasharray: 1000;
          stroke-dashoffset: 0;
        }
        .deviation_data_mdl  .path.circle {
          -webkit-animation: dash .5s ease-in-out;
          animation: dash .5s ease-in-out;
        }
        .deviation_data_mdl  .path.line {
          stroke-dashoffset: 1000;
          -webkit-animation: dash 9s .35s ease-in-out forwards;
          animation: dash 9s .35s ease-in-out forwards;
        }
        .deviation_data_mdl  .path.check {
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
    <div role="tabpanel" class="tab-pane fade in active" id="LeadSaction">                   
        <div id="LeadDetails">
        <p>DEVMUNI LEASING & FINANCE LIMITED is a NBFC registered with RBI having its registered office at 1689/121, Shanti Nagar,
                 Tri Nagar, New Delhi, North West Delhi, Delhi, 110035. The company uses proprietary loan softwares for its various 
                 loan offerings to individual customers in a completely fintech environment.
                </p>
                <div class="deviation_data_mdl"> 
                    <div class="side_panel1"></div>
	
     <details class="style6 left_content1 active">
     <div class="side1"></div>
        <summary class="active"><span>
          <!-- <img src="public/images/bg_img.png"> -->
        Min criteria stability norms</span></summary>
            <div class="content active">
                <table>
                    <thead>
                        <tr>
                            <th>Rule Description</th>
                            <th>Cut Off Value</th>
                            <th>Actual Value</th>
                            <th>Relevant inputs</th>
                            <th>System Decision</th>
                            <th>Manual Decision</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Guarantor Overdue EMI</td>
                            <td>No Overdue</td>
                            <td>
                                <p>Applicant Overdue = 0.0</p>
                                <p>Co-applicant Overdue = 0.0</p>
                                <p>Guarantor Overdue = 0.0</p>
                            </td> 
                            <td class="main_mdl1">
                                <p>Applicant total Overdue : <span>INR 0</span></p> 
                                <p>Co-applicant total Overdue : <span>INR 0</span></p> 
                                <p>Guarantor total Overdue : <span>INR 0</span></p> 
                            </td>
                            <td>Approved  
                                <!--[if lte IE 9]>
                                  <style>
                                    .path {stroke-dasharray: 0 !important;}
                                  </style>
                                <![endif]--> 
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                   <circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                   <polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                </svg> 
                            </td>
                            <td>Approved 
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                    <circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                    <polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                  </svg> 
                            </td>
                        </tr> 
                    </tbody>
               </table> 
            </div>
     </details>
     <details class="style6 left_content1">
<div class="side1"></div>
        <summary class="second_mdl"><span>Civil norms</span></summary> 
            <div class="content">
               <table>
                    <thead>
                        <tr>
                            <th>Rule Description</th>
                            <th>Cut Off Value</th>
                            <th>Actual Value</th>
                            <th>Relevant inputs</th>
                            <th>System Decision</th>
                            <th>Manual Decision</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Cibil write-off</td>
                            <td>No write-off or Settled amount</td>
                            <td>Cibil write-off age = 999999</td>
                            <td>Cibil write-off age :  999999 month</td>
                            <td>Refer <span><img src="<?= base_url(); ?>/public/images/faq.png"></span></td>
                            <td>
                               <p> <select>
                                    <option>Select action</option>
                                    <option>Select action</option>
                                    <option>Select action</option>
                                    <option>Select action</option>
                                    <option>Select action</option>
                                </select></p>
                                <p> Pending L4 Decision </p></td>
                        </tr>
                        <tr>
                            <td>Cibil Score</td>
                            <td>Cibil Error</td>
                            <td>Cibil Score = -1</td>
                            <td>Cibil Score : -1</td>
                            <td>Refer <span><img src="<?= base_url(); ?>/public/images/faq.png"></span></td>
                            <td>
                               <p> <select>
                                    <option>Select action</option>
                                    <option>Select action</option>
                                    <option>Select action</option>
                                    <option>Select action</option>
                                    <option>Select action</option>
                                </select></p>
                                <p> Pending L4 Decision </p></td>
                        </tr>
                    </tbody>
               </table> 
            </div> 
     </details>
     <details class="style6 left_content1">
        <summary class="third_mdl"><span>Eligibility criteria</span></summary>
            <div class="content">
                <table>
                    <thead>
                        <tr>
                            <th>Rule Description</th>
                            <th>Cut Off Value</th>
                            <th>Actual Value</th>
                            <th>Relevant inputs</th>
                            <th>System Decision</th>
                            <th>Manual Decision</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Guarantor Overdue EMI</td>
                            <td>No Overdue</td>
                            <td>
                                <p>Applicant Overdue = 0.0</p>
                                <p>Co-applicant Overdue = 0.0</p>
                                <p>Guarantor Overdue = 0.0</p>
                            </td> 
                            <td class="main_mdl1">
                                <p>Applicant total Overdue : <span>INR 0</span></p> 
                                <p>Co-applicant total Overdue : <span>INR 0</span></p> 
                                <p>Guarantor total Overdue : <span>INR 0</span></p> 
                            </td>
                            <td>Approved  
                                <!--[if lte IE 9]>
                                  <style>
                                    .path {stroke-dasharray: 0 !important;}
                                  </style>
                                <![endif]--> 
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                   <circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                   <polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                </svg> 
                            </td>
                            <td>Approved 
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                    <circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                    <polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                  </svg> 
                            </td>
                        </tr> 
                        <tr>
                            <td>Guarantor Overdue EMI</td>
                            <td>No Overdue</td>
                            <td>
                                <p>Applicant Overdue = 0.0</p>
                                <p>Co-applicant Overdue = 0.0</p>
                                <p>Guarantor Overdue = 0.0</p>
                            </td> 
                            <td class="main_mdl1">
                                <p>Applicant total Overdue : <span>INR 0</span></p> 
                                <p>Co-applicant total Overdue : <span>INR 0</span></p> 
                                <p>Guarantor total Overdue : <span>INR 0</span></p> 
                            </td>
                            <td>Approved   
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                   <circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                   <polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                </svg> 
                            </td>
                            <td>Approved 
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                    <circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                    <polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                  </svg> 
                            </td>
                        </tr> 
                        <tr>
                            <td>Guarantor Overdue EMI</td>
                            <td>No Overdue</td>
                            <td>
                                <p>Applicant Overdue = 0.0</p>
                                <p>Co-applicant Overdue = 0.0</p>
                                <p>Guarantor Overdue = 0.0</p>
                            </td> 
                            <td class="main_mdl1">
                                <p>Applicant total Overdue : <span>INR 0</span></p> 
                                <p>Co-applicant total Overdue : <span>INR 0</span></p> 
                                <p>Guarantor total Overdue : <span>INR 0</span></p> 
                            </td>
                            <td>Not Applicable   
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                    <circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                    <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3"/>
                                    <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2"/>
                                  </svg>
                            </td>
                            <td>Not Applicable  
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                    <circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                    <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3"/>
                                    <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2"/>
                                  </svg>
                            </td>
                        </tr> 
                    </tbody>
               </table> 
            </div> 
     </details>
     <details class="style6 left_content1">
        <summary class="second_mdl"><span> STP</span></summary>
           
            <div class="content">
                <p>
                   effrf
                </p>   
            </div> 
     </details>
     <details class="style6">
        <summary class="third_mdl"><span>view all deviations</span></summary>
            <div class="content">
                  <p>
                   effrf
                  </p>  
            </div> 
     </details> 
    </div> 
        </div>
        </div>
    </div>
</div>