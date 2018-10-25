import Home from './components/home/Home.vue';
import Login from './components/login/Login.vue';
import EditPassword from './components/profile/edit-password/EditPassword.vue';
import ProfileWrapper from './components/profile/ProfileWrapper.vue';
import Profile from './components/profile/Profile.vue';
import EditProfile from './components/profile/edit-profile/EditProfile.vue';

import AddPublisher from './components/publisher/add-publisher/AddPublisher.vue';
import PublisherWrapper from './components/publisher/PublisherWrapper.vue';
import Publisher from './components/publisher/Publisher.vue';
import EditPublisher from './components/publisher/edit-publisher/EditPublisher.vue';

import AddClient from './components/client/add-client/AddClient.vue';
import ClientWrapper from './components/client/ClientWrapper.vue';
import Client from './components/client/Client.vue';
import EditClient from './components/client/edit-client/EditClient.vue';

import AddSurvey from './components/survey/add-survey/AddSurvey.vue';
import SurveyWrapper from './components/survey/SurveyWrapper.vue';
import Survey from './components/survey/Survey.vue';
import EditSurvey from './components/survey/edit-survey/EditSurvey.vue';

export default [
	{
		path: '/',
		name: 'index',
		component: Home,
		meta: {}
	},
	{
		path: '/login',
		name: 'login',
		component: Login,
		meta: {requiresGuest: true}
	},
	{
		path: '/profile',
		component: ProfileWrapper,
		children: [
			{
				path: '',
				name: 'profile',
				component: Profile,
				meta: {requiresAuth: true}
			},
			{
				path: 'edit-profile',
				name: 'profile.editProfile',
				component: EditProfile,
				meta: {requiresAuth: true}
			},
			{
				path: 'edit-password',
				name: 'profile.editPassword',
				component: EditPassword,
				meta: {requiresAuth: true}
			},
			{
				path: '*',
				redirect: {
					name: 'profile'
				}
			}
		]
	},
	{
		path: '/publisher',
		component: PublisherWrapper,
		children: [
			{
				path: '',
				name: 'publisher',
				component: Publisher,
				meta: {requiresAuth: true}
			},
			{
				path: 'edit-publisher/:id',
				name: 'publisher.editPublisher',
				component: EditPublisher,
				props: true,
				meta: {requiresAuth: true}
			},
			{
				path: 'add-publisher',
				name: 'publisher.addPublisher',
				component: AddPublisher,
				meta: {requiresAuth: true}
			},
			{
				path: '*',
				redirect: {
					name: 'publisher'
				}
			}
		]
	},
	{
		path: '/client',
		component: ClientWrapper,
		children: [
			{
				path: '',
				name: 'client',
				component: Client,
				meta: {requiresAuth: true}
			},
			{
				path: 'edit-client/:id',
				name: 'client.editClient',
				component: EditClient,
				props: true,
				meta: {requiresAuth: true}
			},
			{
				path: 'add-client',
				name: 'client.addClient',
				component: AddClient,
				meta: {requiresAuth: true}
			},
			{
				path: '*',
				redirect: {
					name: 'client'
				}
			}
		]
	},

	{
		path: '/survey',
		component: SurveyWrapper,
		children: [
			{
				path: '',
				name: 'survey',
				component: Survey,
				meta: {requiresAuth: true}
			},
			{
				path: 'edit-survey/:id',
				name: 'survey.editSurvey',
				component: EditSurvey,
				props: true,
				meta: {requiresAuth: true}
			},
			{
				path: 'add-survey',
				name: 'survey.addSurvey',
				component: AddSurvey,
				meta: {requiresAuth: true}
			},
			{
				path: '*',
				redirect: {
					name: 'survey'
				}
			}
		]
	},
];