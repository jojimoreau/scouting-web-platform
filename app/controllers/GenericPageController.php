<?php

abstract class GenericPageController extends BaseController {
  
  protected abstract function canEdit();
  protected abstract function getShowRouteName();
  protected abstract function getEditRouteName();
  protected abstract function isSectionPage();
  protected abstract function getPageType();
  protected abstract function getPageTitle();

  public function showPage() {
    $page = $this->getPage();
    return View::make('pages.page')
            ->with('page_content', $page->content_html)
            ->with('page_title', $this->getPageTitle())
            ->with('edit_url', URL::route($this->getEditRouteName(), array("section_slug" => View::shared('user')->currentSection->slug)))
            ->with('can_edit', $this->canEdit());
  }
  
  public function showEdit() {
    if (!$this->canEdit()) {
      return Illuminate\Http\Response::create(View::make('forbidden'), Illuminate\Http\Response::HTTP_FORBIDDEN);
    }
    $page = $this->getPage();
    return View::make('pages.editPage')
            ->with('page_content', $page->content_markdown)
            ->with('page_title', $this->getPageTitle())
            ->with('original_page_url', URL::route($this->getShowRouteName()));
  }
  
  public function savePage() {
    if (!$this->canEdit()) {
      return Illuminate\Http\Response::create(View::make('forbidden'), Illuminate\Http\Response::HTTP_FORBIDDEN);
    }
    $newContent = Input::get('page_content');
    $page = $this->getPage();
    $page->content_markdown = $newContent;
    $page->content_html = \Michelf\Markdown::defaultTransform($newContent);
    $page->save();
    return Illuminate\Http\RedirectResponse::create(URL::route($this->getEditRouteName(), array("section_slug" => View::shared('user')->currentSection->slug)));
  }
  
  protected function getPage() {
    $sectionId = 1;
    if ($this->isSectionPage()) {
      $sectionId = View::shared('user')->currentSection->id;
    }
    $page = Page::where('section_id', '=', $sectionId)->where('type', '=' , $this->getPageType())->first();
    if (!$page) {
      $page = Page::create(array(
          "type" => $this->getPageType(),
          "section_id" => $sectionId,
          "content_html" => "<h1>Cette page n'existe pas encore.</h1>",
          "content_markdown" => "# Tape ici le titre le la page\n\nTape ici le contenu de la page.\n\nRegarde l'exemple de droite si tu veux faire une mise en page avancée.",
      ));
    }
    return $page;
  }
  
}
