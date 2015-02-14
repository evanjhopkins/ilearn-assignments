import re
import mechanize
import sys
from lxml import html

def main():
	br = mechanize.Browser()
	
	# load login page
	br.open("https://login.marist.edu/cas/login?service=https%3A%2F%2Filearn.marist.edu%2Fsakai-login-tool%2Fcontainer")
	
	# <br/> --> <br /> bug fix
	# http://stackoverflow.com/questions/2394420/python-mechanize-ignores-form-input-in-the-html
	response = br.response()
	response.set_data(response.get_data().replace("<br/>", "<br />")) #Python mechanize is broken, fixing it.
	br.set_response(response)
	
	# filling out login form
	br.select_form(nr=0)
	br["username"] = "steh"
	br["password"] = "evantoni"

	# submit form, redirects to ilearn homepage
	response = br.submit()

	# go to membership page
	br.follow_link(text_regex="Membership")

	# getting raw source to parse out data
	response = br.response()
	source = response.read()
	tree = html.fromstring(source)

	# selecting list of semesters
	semesters = tree.xpath('//ul[@class="otherSitesCategorList"]')

	# getting first semester (most recent)
	current_semester =  semesters[0]
	
	# for each class, get name and id
	# put classes into a dicitonary
	i=0
	classes = {}
	for aclass in current_semester:
		class_name =  aclass[0][0].text 
		class_id = aclass[1].get("id")

		# open class page
		br.open("https://ilearn.marist.edu/portal/site/"+class_id)

		# go to assignments page
		br.follow_link(text_regex="Assignments")

		# getting raw source to parse out data
		response = br.response()
		source = response.read()
		tree = html.fromstring(source)

		# get link to iframe containing actual assignments page and go to it
		src_url = tree.cssselect("iframe")[0].attrib['src']
		br.open(src_url)

		# getting raw source to parse out data
		response = br.response()
		source = response.read()
		tree = html.fromstring(source)

		titles = tree.xpath('//td[@headers="title"]')
		statuss = tree.xpath('//td[@headers="status"]')
		dueDates =  tree.xpath('//td[@headers="dueDate"]')
		openDates = tree.xpath('//td[@headers="openDate"]')
		
		assignments = {}
		j=0
		for title in titles:
			titlee = title[0][0].text.strip()
			status = statuss[j].text.strip()
			dueDate = dueDates[j].text.strip()
			openDate = openDates[j].text.strip()
			assignments[j] = {'title':titlee, 'status':status, 'dueDate':dueDate, 'openDate':openDate}
			j = j+1

		classes[i] = {'name':class_name, 'id':class_id, 'assignments':assignments}
		i = i+1
	print type(classes)



if __name__ == "__main__":
    main()
