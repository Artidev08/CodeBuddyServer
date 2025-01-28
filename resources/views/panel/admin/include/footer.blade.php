 <footer class="footer "style="bottom: 0px;position: fixed;width: 100%;">
     <div class="w-100 clearfix">
         <span class="text-center text-sm-left d-md-inline-block footer-margin-l">
             {{ str_replace('{date}', date('Y'), getSetting('copyright_text')) }} @lang(env('APP_VERSION'))
         </span>
         <span class="float-sm-right mt-1 mt-sm-0 text-center footer-margin-r mb-0">
             @lang('admin/ui.developed&designedby')
             <a href="https://www.defenzelite.com" class="text-dark" target="_blank">
                  @lang('admin/ui.DefenzelitePvt.Ltd')
             </a>
         </span>
     </div>
 </footer>
