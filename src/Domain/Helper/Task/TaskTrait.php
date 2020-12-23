<?php /** @noinspection ALL */

namespace App\Domain\Helper\Task;

use App\Domain\Helper\Tag\Entity\Tag;
use App\Domain\Helper\Tag\Entity\Types\Id as TagId;
use App\Domain\Helper\Tag\TagRepository;
use App\Domain\Helper\Task\Entity\Task;

trait TaskTrait
{
    private function addTags(Task $task, $command, TagRepository $tagRepository)
    {
        if(empty($command->tags)) { return; }

        $names = array_column($command->tags, 'name');

        if($tags = $tagRepository->findBy(['name' => $names])) {
            array_map(function ($elem) use ($task, $tags, $names) {
                /** @var Tag $tag */
                foreach ($tags as $tag) {
                    if ($tag->getName()===$elem['name']) {
                        $task->addTag($tag);
                        return;
                    }
                }
                $task->addTag(new Tag(TagId::next(), $elem['name']));
            }, $command->tags);
        }

    }

    private function removeTags(Task $task, $command)
    {
        try {
            if( $tags = $task->getTags()) {
                if(is_array($command->tags)) {
                    $names = array_column($command->tags, 'name');
    //            var_dump($names);
                    foreach ($tags as $tag) {
                        if (!in_array($tag->getName(), $names)) {
                            $task->removeTag($tag);
                        }
                    }
                }
            }
        } catch (\Exception $exception) {
            //var_dump($exception->getMessage());
            //var_dump($exception->getTrace());
        }

    }
}
