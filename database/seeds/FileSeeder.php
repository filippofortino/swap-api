<?php

use Illuminate\Database\Seeder;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\File::class, 150)->create();
        $folderIds = App\Models\Folder::pluck('id');

        // This is not optimal, some files might be inside multiple folders, 
        // which is imbossible. However, that should be ok for testing purposes.
        foreach(App\Models\File::all() as $file) {
            if(rand(0, 1)) // Do not update some records
                $file->update(['folder_id' => $folderIds->random()]);
        }

    }
}
