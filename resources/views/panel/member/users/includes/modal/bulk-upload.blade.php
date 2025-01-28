 <!-- Modal -->
 <div class="modal fade" id="UserBulkUpload" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-md" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLongTitle">Bulk Import</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form action="{{ route('panel.admin.bulk.user') }}" method="post" enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" name="request_with" value="upload">
                     <div>
                         <div class="alert alert-info" style="padding: 0.75rem 1rem;">
                             <p class="mb-0">First letter of role name should be capital.There are
                                 {{ @$roles->count() }} Role in our platform.</p>
                             <ul>
                                 @foreach (@$roles as $role)
                                     <li>{{ @$role }}</li>
                                 @endforeach
                             </ul>
                         </div>
                     </div>
                     <div class="d-flex justify-content-end">
                         <a href="{{ asset('utility/bulk-user/user-agent.xlsx') }}" class="btn btn-link"
                             download=""><i class="ik ik-arrow-down"></i> Download Template</a>
                     </div>
                     <div class="form-group">
                         <label for="">Upload Updated Excel Template</label>
                         <input reuired type="file" class="form-control" name="file"
                             accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                     </div>
                     <x-button>
                         Upload
                     </x-button>
                 </form>
             </div>
         </div>
     </div>
 </div>
