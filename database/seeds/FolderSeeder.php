<?php

use Illuminate\Support\Str;
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
        $root = new App\Models\Folder;
        $root->uuid = Str::uuid();
        $root->name = 'root';
        $root->folder_id = 1;
        
        $root->save();

        factory(App\Models\Folder::class, 80)->create();
        $folderIds = App\Models\Folder::pluck('id')->skip(1);

        // This is not optimal, some folders might be inside multiple folders, 
        // which is imbossible. However, that should be ok for testing purposes.
        foreach($folderIds as $id) {
            if(rand(0, 1)) // Do not update some records
                App\Models\Folder::where('id', $id)->update(['folder_id' => $folderIds->random()]);
        }

    }
}
