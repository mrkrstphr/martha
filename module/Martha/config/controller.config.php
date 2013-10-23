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
        'Martha\Controller\Build' => function (Zend\Mvc\Controller\ControllerManager $cm) {
            return new Martha\Controller\BuildController(
                $cm->getServiceLocator()->get('ProjectRepository'),
                $cm->getServiceLocator()->get('BuildRepository')
            );
        },
        'Martha\Controller\Dashboard' => function (Zend\Mvc\Controller\ControllerManager $cm) {
            return new Martha\Controller\DashboardController(
                $cm->getServiceLocator()->get('ProjectRepository'),
                $cm->getServiceLocator()->get('BuildRepository')
            );
        },
        'Martha\Controller\Plugin' => function (Zend\Mvc\Controller\ControllerManager $cm) {
            return new Martha\Controller\PluginController(
                $cm->getServiceLocator()->get('System')
            );
        },
        'Martha\Controller\Projects' => function (Zend\Mvc\Controller\ControllerManager $cm) {
            return new Martha\Controller\ProjectsController(
                $cm->getServiceLocator()->get('System'),
                $cm->getServiceLocator()->get('ProjectRepository'),
                $cm->getServiceLocator()->get('BuildRepository')
            );
        },
    ]
];
