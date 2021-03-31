<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public function blogPosts()
    {
        // withTimestamps() call, the time declaration will create whenever relation created in database(created_at, updated_at), since CreateTagsTable has $table->timestamps();
        return $this->belongsToMany(BlogPost::class)->withTimestamps()->as("tagged"); // as() see line 166
        // * Episode 170
        // * This the way to query attach relation, either many to many, one to one nor one to many relation
        // >>> $blogPost->tags()->sync([1,2]);
        // => [
        //      "attached" => [
        //        1,
        //        2,
        //      ],
        //      "detached" => [],
        //      "updated" => [],
        //    ]
        // >>> $tags = $blogPost->tags;
        // => Illuminate\Database\Eloquent\Collection {#4339
        //     all: [
        //       App\Models\Tag {#4362
        //         id: 1,
        //         name: "Science",
        //         created_at: "2021-03-30 10:03:47",
        //         updated_at: "2021-03-30 10:03:47",
        //         pivot: Illuminate\Database\Eloquent\Relations\Pivot {#4335
        //           blog_post_id: 1,
        //           tag_id: 1,
        //           created_at: "2021-03-31 02:09:38",
        //           updated_at: "2021-03-31 02:09:38",
        //         },
        //       },
        //       App\Models\Tag {#4354
        //         id: 2,
        //         name: "Politics",
        //         created_at: "2021-03-30 10:04:36",
        //         updated_at: "2021-03-30 10:04:36",
        //         pivot: Illuminate\Database\Eloquent\Relations\Pivot {#4338
        //           blog_post_id: 1,
        //           tag_id: 2,
        //           created_at: "2021-03-31 02:09:38",
        //           updated_at: "2021-03-31 02:09:38",
        //         },
        //       },
        //     ],
        //   }




        //Episode 170 in regard to Pivot table
        // >>> $tags[0]->pivot;
        // => Illuminate\Database\Eloquent\Relations\Pivot {#4335
        //      blog_post_id: 1,
        //      tag_id: 1,
        //      created_at: "2021-03-31 02:09:38",
        //      updated_at: "2021-03-31 02:09:38",
        //    }
        // >>> $tags[0]->pivot->created_at;
        // => Illuminate\Support\Carbon @1617156578 {#4355
        //      date: 2021-03-31 02:09:38.0 UTC (+00:00),
        //    }
        // >>> $tag = Tag::find(1);
        // [!] Aliasing 'Tag' to 'App\Models\Tag' for this Tinker session.
        // => App\Models\Tag {#4358
        //      id: 1,
        //      name: "Science",
        //      created_at: "2021-03-30 10:03:47",
        //      updated_at: "2021-03-30 10:03:47",
        //    }
        // >>> $posts = $tag->blogPosts;
        // => null
        // >>> >>> $posts = $tag->blogPost;
        // => Illuminate\Database\Eloquent\Collection {#4343
        //      all: [
        //        App\Models\BlogPost {#4382
        //          id: 1,
        //          created_at: "2021-03-18 03:03:13",
        //          updated_at: "2021-03-30 07:38:38",
        //          title: "Ut aperiam quasi aliquid sed nemo autem dolores inventore quod non.",
        //          content: """
        //            Dolorem voluptate non autem ducimus. Sit cum fugiat optio voluptas placeat magnam possimus. Autem odit quidem perspiciatis asperiores aut. Est qui placeat vel explicabo mollitia.\n
        //            \n
        //            Maxime saepe aut iste. Vel ea cumque natus necessitatibus provident in. Repudiandae et atque quo explicabo est soluta ipsum quo.\n
        //            \n
        //            Est qui nemo eos porro voluptatem qui. Esse possimus possimus et optio ea eum. Fugiat velit non et ducimus et sint. Est laboriosam rerum ea ex.\n
        //            \n
        //            Ut praesentium sunt assumenda tenetur quibusdam vitae. Aperiam deleniti laborum eaque. Ratione possimus esse quia. Voluptatum consequuntur laborum doloremque voluptas.\n
        //            \n
        //            Temporibus id nam temporibus ea qui pariatur hic quam. Laboriosam est aut saepe quia. Repellendus sit et aut sunt. Praesentium incidunt in aut laboriosam expedita sint.
        //            """,
        //          user_id: 2,
        //          deleted_at: null,
        //          pivot: Illuminate\Database\Eloquent\Relations\Pivot {#4381
        //            tag_id: 1,
        //            blog_post_id: 1,
        //            created_at: "2021-03-31 02:09:38",
        //            updated_at: "2021-03-31 02:09:38",
        //          },
        //        },
        //      ],
        //    }




        //* Query Pivot and Change pivot to tagged, but its an optional!
        // >>> $tag = Tag::find(1);
        // [!] Aliasing 'Tag' to 'App\Models\Tag' for this Tinker session.
        // => App\Models\Tag {#4333
        //      id: 1,
        //      name: "Science",
        //      created_at: "2021-03-30 10:03:47",
        //      updated_at: "2021-03-30 10:03:47",
        //    }
        // >>> $posts = $tag->blogPosts->first();
        // => App\Models\BlogPost {#4370
        //      id: 1,
        //      created_at: "2021-03-18 03:03:13",
        //      updated_at: "2021-03-30 07:38:38",
        //      title: "Ut aperiam quasi aliquid sed nemo autem dolores inventore quod non.",
        //      content: """
        //        Dolorem voluptate non autem ducimus. Sit cum fugiat optio voluptas placeat magnam possimus. Autem odit quidem perspiciatis asperiores aut. Est qui placeat vel explicabo mollitia.\n
        //        \n
        //        Maxime saepe aut iste. Vel ea cumque natus necessitatibus provident in. Repudiandae et atque quo explicabo est soluta ipsum quo.\n
        //        \n
        //        Est qui nemo eos porro voluptatem qui. Esse possimus possimus et optio ea eum. Fugiat velit non et ducimus et sint. Est laboriosam rerum ea ex.\n
        //        \n
        //        Ut praesentium sunt assumenda tenetur quibusdam vitae. Aperiam deleniti laborum eaque. Ratione possimus esse quia. Voluptatum consequuntur laborum doloremque voluptas.\n
        //        \n
        //        Temporibus id nam temporibus ea qui pariatur hic quam. Laboriosam est aut saepe quia. Repellendus sit et aut sunt. Praesentium incidunt in aut laboriosam expedita sint.
        //        """,
        //      user_id: 2,
        //      deleted_at: null,
        //      tagged: Illuminate\Database\Eloquent\Relations\Pivot {#4369
        //        tag_id: 1,
        //        blog_post_id: 1,
        //        created_at: "2021-03-31 02:09:38",
        //        updated_at: "2021-03-31 02:09:38",
        //      },
        //    }
        // >>> $posts->tagged->created_at;
        // => Illuminate\Support\Carbon @1617156578 {#4344
        //      date: 2021-03-31 02:09:38.0 UTC (+00:00),
        //    }
        // >>> $post = BlogPost::find(1);
        // [!] Aliasing 'BlogPost' to 'App\Models\BlogPost' for this Tinker session.
        // => App\Models\BlogPost {#4373
        //      id: 1,
        //      created_at: "2021-03-18 03:03:13",
        //      updated_at: "2021-03-30 07:38:38",
        //      title: "Ut aperiam quasi aliquid sed nemo autem dolores inventore quod non.",
        //      content: """
        //        Dolorem voluptate non autem ducimus. Sit cum fugiat optio voluptas placeat magnam possimus. Autem odit quidem perspiciatis asperiores aut. Est qui placeat vel explicabo mollitia.\n
        //        \n
        //        Maxime saepe aut iste. Vel ea cumque natus necessitatibus provident in. Repudiandae et atque quo explicabo est soluta ipsum quo.\n
        //        \n
        //        Est qui nemo eos porro voluptatem qui. Esse possimus possimus et optio ea eum. Fugiat velit non et ducimus et sint. Est laboriosam rerum ea ex.\n
        //        \n
        //        Ut praesentium sunt assumenda tenetur quibusdam vitae. Aperiam deleniti laborum eaque. Ratione possimus esse quia. Voluptatum consequuntur laborum doloremque voluptas.\n
        //        \n
        //        Temporibus id nam temporibus ea qui pariatur hic quam. Laboriosam est aut saepe quia. Repellendus sit et aut sunt. Praesentium incidunt in aut laboriosam expedita sint.
        //        """,
        //      user_id: 2,
        //      deleted_at: null,
        //    }
        // >>> $post->tags;
        // => Illuminate\Database\Eloquent\Collection {#4365
        //      all: [
        //        App\Models\Tag {#4366
        //          id: 1,
        //          name: "Science",
        //          created_at: "2021-03-30 10:03:47",
        //          updated_at: "2021-03-30 10:03:47",
        //          pivot: Illuminate\Database\Eloquent\Relations\Pivot {#4340
        //            blog_post_id: 1,
        //            tag_id: 1,
        //            created_at: "2021-03-31 02:09:38",
        //            updated_at: "2021-03-31 02:09:38",
        //          },
        //        },
        //        App\Models\Tag {#4361
        //          id: 2,
        //          name: "Politics",
        //          created_at: "2021-03-30 10:04:36",
        //          updated_at: "2021-03-30 10:04:36",
        //          pivot: Illuminate\Database\Eloquent\Relations\Pivot {#4349
        //            blog_post_id: 1,
        //            tag_id: 2,
        //            created_at: "2021-03-31 02:09:38",
        //            updated_at: "2021-03-31 02:09:38",
        //          },
        //        },
        //      ],
        //    }
    }
}
