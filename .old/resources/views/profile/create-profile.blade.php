@extends('layouts.profile')

@section('content')

   <section class="row" style="margin-top:40px">
       <div class="col-md-12">
            
           <form role="form" action="/store-profile" autocomplete="off" method="POST" enctype="multipart/form-data">
            {{ @csrf_field() }}
            <input type="text" name="user_id" value="{{ Auth::user()->id}}">
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                            <input type="file" 
                            class="filepond"
                            name="filepond"
                            accept="image/png, image/jpeg, image/gif"/>
                        <label for="">Avatar/Profile Picture</label>
                        <input type="file" name="profile_img" id="profile_img" class="form-control" accept=".jpg,.png,.gif">
                    </div>
                    <div class="form-group">
                        <label for="">Cover Photo</label>
                        <input type="file" name="cover_img" id="cover_img" class="form-control"  accept=".jpg,.png,.gif">
                    </div>
                    <div class="form-group">
                    <input type="text" name="first_name" value="{{ $myProfile['first_name'] ?? '' }}" data-role="tagsinput" class="form-control" placeholder="First Name" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" value="{{ $myProfile['last_name'] ?? '' }}" name="last_name"  required placeholder="Last Name">
                </div>
                <div class="form-group">
                    <input type="text" name="profile-name" class="form-control" placeholder="Name Your Profile">
                    <small>This will be used to find you in searches and in the URL http://mysportsshare.com/profile-name </small>
                </div>
                <div class="form-group"><label for="">About Me</label>
                <textarea name="bio" class="form-control">{{ $user->profile->bio ?? '' }}</textarea>
                </div>
            </div>
            <div class="col-md-5">
                @if ($user->profile_img)
                    <img src="{{ Storage::disk('public')->url($myProfile->profile_img) }}" alt="">
                    @else
                    <div class="image-placeholder col-md-12">
                            <h4>image placeholder text</h4>
                        </div>
                @endif
                    
            </div>
            </div>
            
            
            <div class="form-group">
                    <label for="">Facebook <span class="fa fa-facebook"></label>
                    <input type="text" name="fb" class="form-control" placeholder="Facebook">
            </div>
            <div class="form-group">
                    <label for="">Twitter <span class="fa fa-twitter"></label>
                    <input type="text" name="twitter" class="form-control" placeholder="Twitter">
            </div>
            <div class="form-group">
                    <label for="">Instagram <span class="fa fa-instagram"></label>
                    <input type="text" name="instagram" class="form-control" placeholder="Instagram">
            </div>
                  <input type="submit" value="Make It Happen">      
        </form>
       </div>
       
    </section>
    
@endsection
@push('scripts')
<script>
/*
We need to register the required plugins to do image manipulation and previewing.
*/
FilePond.registerPlugin(
	// encodes the file as base64 data
  FilePondPluginFileEncode,
	
	// validates files based on input type
  FilePondPluginFileValidateType,
	
	// corrects mobile image orientation
  FilePondPluginImageExifOrientation,
	
	// previews the image
  FilePondPluginImagePreview,
	
	// crops the image to a certain aspect ratio
  FilePondPluginImageCrop,
	
	// resizes the image to fit a certain size
  FilePondPluginImageResize,
	
	// applies crop and resize information on the client
  FilePondPluginImageTransform
);

// Select the file input and use create() to turn it into a pond
// in this example we pass properties along with the create method
// we could have also put these on the file input element itself
FilePond.create(
	document.querySelector('input'),
	{
		labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
    imagePreviewHeight: 170,
    imageCropAspectRatio: '1:1',
    imageResizeTargetWidth: 200,
    imageResizeTargetHeight: 200,
    stylePanelLayout: 'compact circle',
    styleLoadIndicatorPosition: 'center bottom',
    styleButtonRemoveItemPosition: 'center bottom'
	}
);
</script>
@endpush
