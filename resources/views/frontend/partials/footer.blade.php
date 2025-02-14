 <!--Footer-->
 <footer
 class="relative bg-gradient-to-r from-orange-500 to-red-500 pt-8 pb-6"
>
 <div class="max-w-7xl mx-auto px-4">
   <div class="flex flex-wrap text-left lg:text-left">
     <div class="w-full lg:w-6/12 px-4">
       <h4 class="text-3base text-white font-semibold text-blueGray-700">
         Let's stay connected!
       </h4>
       <h5 class="text-lg mt-0 mb-2 text-white">
         Find us on any of these platforms. We usually respond within 1-2
         business days.
       </h5>
       <div class="mt-6 lg:mb-0 mb-6">
         <button
           class="bg-white text-lightBlue-400 shadow-lg font-normal h-10 w-10 items-center justify-center align-center rounded-full outline-none focus:outline-none mr-2"
           type="button"
         >
           <i class="fab fa-twitter"></i>
         </button>
         <button
           class="bg-white text-lightBlue-600 shadow-lg font-normal h-10 w-10 items-center justify-center align-center rounded-full outline-none focus:outline-none mr-2"
           type="button"
         >
           <i class="fab fa-facebook-square"></i>
         </button>
         <button
           class="bg-white text-pink-400 shadow-lg font-normal h-10 w-10 items-center justify-center align-center rounded-full outline-none focus:outline-none mr-2"
           type="button"
         >
           <i class="fab fa-dribbble"></i>
         </button>
         <button
           class="bg-white text-blueGray-800 shadow-lg font-normal h-10 w-10 items-center justify-center align-center rounded-full outline-none focus:outline-none mr-2"
           type="button"
         >
           <i class="fab fa-github"></i>
         </button>
       </div>
     </div>
     <div class="w-full lg:w-6/12 px-4">
       <div class="flex flex-wrap items-top mb-6">
         <div class="w-full lg:w-4/12 px-4 ml-auto">
           <span
             class="block uppercase text-white text-lg font-semibold mb-4"
             >Useful Links</span
           >
           <ul class="list-unstyled">
             <li>
               <a
                 class="text-white hover:text-blueGray-800 font-semibold block pb-2 text-sm"
                 href="#"
                 >About Us</a
               >
             </li>
             <li>
               <a
                 class="text-white hover:text-blueGray-800 font-semibold block pb-2 text-sm"
                 href="#"
                 >Blog</a
               >
             </li>
             <li>
               <a
                 class="text-white hover:text-blueGray-800 font-semibold block pb-2 text-sm"
                 href="appetizer.html"
                 >Appetizer Foods</a
               >
             </li>
             <li>
               <a
                 class="text-white hover:text-blueGray-800 font-semibold block pb-2 text-sm"
                 href="#"
                 >Best Foods</a
               >
             </li>
           </ul>
         </div>
         <div class="w-full lg:w-4/12 px-4">
           <span
             class="block uppercase text-white text-lg font-semibold mb-4"
             >Other Resources</span
           >
           <ul class="list-unstyled">
             <li>
               <a
                 class="text-white hover:text-blueGray-800 font-semibold block pb-2 text-sm"
                 href="#"
                 >Terms & Conditions</a
               >
             </li>
             <li>
               <a
                 class="text-white hover:text-blueGray-800 font-semibold block pb-2 text-sm"
                 href="#"
                 >Privacy Policy</a
               >
             </li>
             <li>
               <a
                 class="text-white hover:text-blueGray-800 font-semibold block pb-2 text-sm"
                 href="#"
                 >Contact Us</a
               >
             </li>
           </ul>
         </div>
       </div>
     </div>
   </div>
   <hr class="my-6 border-blueGray-300" />
   <div
     class="flex flex-wrap items-center md:justify-between justify-center"
   >
     <div class="w-full md:w-4/12 px-4 mx-auto text-center">
       <div class="text-sm text-white font-semibold py-1">
         Developed <span id="get-current-year">By</span>
         <a
           href="https://blacktechcorp.com/"
           class="text-white hover:text-gray-800"
           target="_blank"
           >BlackTech</a
         >
         <a
           href="https://blacktechcorp.com/"
           class="text-white hover:text-blueGray-800"
           >Consultancy</a
         >.
       </div>
     </div>
   </div>
 </div>
</footer>
<!--End Footer-->
</div>

<!-- bootstrap 5 cdn js  -->



@include('frontend.partials.js')

<script src="{{asset('frontend/assets/js/index.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
<!-- end  -->


<!-- jquery  -->


<!-- jquery end -->






<!-- custom linked css  -->
<script src="{{asset('frontend/assets/js/app.js')}}">

</script>



</body>

</html>
