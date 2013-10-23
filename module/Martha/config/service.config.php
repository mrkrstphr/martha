<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <https://github.com/martha-ci>.
 */

return [
    'factories' => [
        'RepositoryFactory' => function (Zend\ServiceManager\ServiceManager $sm) {
            $entityManager = $sm->get('Doctrine\ORM\EntityManager');
            return new Martha\Core\Persistence\Repository\Factory($entityManager);
        },
        'BuildRepository' => function (Zend\ServiceManager\ServiceManager $sm) {
            $factory = $sm->get('RepositoryFactory');
            return $factory->createBuildRepository();
        },
        'ProjectForm' => function (Zend\ServiceManager\ServiceManager $sm) {
            $entityManager = $sm->get('Doctrine\ORM\EntityManager');
            $form = (new Martha\Form\Project\Create())
                ->setHydrator(new DoctrineModule\Stdlib\Hydrator\DoctrineObject($entityManager));
            return $form;
        },
        'ProjectRepository' => function (Zend\ServiceManager\ServiceManager $sm) {
            $factory = $sm->get('RepositoryFactory');
            return $factory->createProjectRepository();
        },
        'Navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        'System' => function (Zend\ServiceManager\ServiceManager $sm) {
            return Martha\Core\System::getInstance();
        }
    ]
];
