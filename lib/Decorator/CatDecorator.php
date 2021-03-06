<?php

class CatDecorator {

  private $controller ;
  private $template ;
  private $job ;
  private $project_completion_feature_enabled ;

  public function  __construct( catController $controller, PHPTAL $template ) {

    $this->controller = $controller ;
    $this->template = $template ;
    $this->job = $this->controller->getJob() ;

    $this->project_completion_feature_enabled =
      $this->job->isFeatureEnabled( Features::PROJECT_COMPLETION );

  }

  public function decorate() {
    $this->template->header = new HeaderDecorator( $this->controller ) ;

    $this->projectCompletionFeature();
    // TODO: add future presentation logic here
  }

  private function projectCompletionFeature() {
    $this->template->projectCompletionFeature = $this->project_completion_feature_enabled ;

  }

}
