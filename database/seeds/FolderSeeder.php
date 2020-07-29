<?php

use Illuminate\Database\Seeder;

class FolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Folder::class, 80)->create();
        $folderIds = App\Models\Folder::pluck('id');

        // This is not optimal, some folders might be inside multiple folders, 
        // which is imbossible. However, that should be ok for testing purposes.
        foreach($folderIds as $id) {
            if(rand(0, 1)) // Do not update some records
                App\Models\Folder::where('id', $id)->update(['folder_id' => $folderIds->random()]);
        }

    }
}
