<?php

namespace App\Jobs;

use Image;
use File;
use App\Models\Design;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Design
     */
    protected $design;

    /**
     * Create a new job instance.
     *
     * @param Design $design
     */
    public function __construct(Design $design)
    {
        $this->design = $design;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $disk = $this->design->disk;
        $filename = $this->design->image;
        $original_file = storage_path().'/uploads/original/'.$filename;

        try {
            // create the large image and save to tmp disk
            Image::make($original_file)->fit(800, 600, function($constraint) {
                $constraint->aspectRatio();
            })->save($large = storage_path('uploads/large/' . $filename));

            // create the thumbnail image
            Image::make($original_file)->fit(250, 200, function($constraint) {
                $constraint->aspectRatio();
            })->save($thumbnail = storage_path("uploads/thumbnail/$filename"));

            // store images to permanent disk original image
            if (Storage::disk($disk)->put("uploads/designs/original/$filename", fopen($original_file, 'r+'))) {
                File::delete($original_file);
            }

            // large image
            if (Storage::disk($disk)->put("uploads/designs/large/$filename", fopen($large, 'r+'))) {
                File::delete($large);
            }

            // thumbnail image
            if (Storage::disk($disk)->put("uploads/designs/thumbnail/$filename", fopen($thumbnail, 'r+'))) {
                File::delete($thumbnail);
            }

            // update the database record with success flag
            $this->design->update([
                'upload_successful' => true
            ]);

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
